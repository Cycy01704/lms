<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\BorrowTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    const FINE_OVERDUE = 250.00;
    const FINE_DAMAGED = 100.00;
    const FINE_LOST = 200.00;

    public function index()
    {
        $user = User::find(Auth::user()->id);

        // Only books with available stock
        $books = Book::where('available_copies', '>', 0)->get();

        // Only admins can select users
        $users = $user->usertype === 'admin' ? User::where('usertype', 'user')->get() : null;

        // Default due date: 7 days from today
        $defaultDueDate = Carbon::now()->addDays(7)->format('Y-m-d');

        $books = Book::all();
        $transactions = BorrowTransaction::with('book', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('borrow.index', compact('transactions', 'books', 'users', 'defaultDueDate'));
    }

    public function books(Request $request){
        
        $user = Auth::user();
        $search = $request->query('search');
        $showReturned = $request->query('showReturned', 'false') === 'true';

    $query = BorrowedBook::with(['book', 'user', 'borrowTransaction']);

    if ($user->usertype !== 'admin') {
        $query->whereHas('borrowTransaction', function ($q) {
            $q->where('user_id', Auth::id());
        });
    }

    if ($showReturned) {
        $query->whereHas('borrowTransaction', function ($q) {
            $q->where('status', 'returned');
        });
    } else {
        $query->whereHas('borrowTransaction', function ($q) {
            $q->where('status', '!=', 'returned');
        });
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('book', function ($q2) use ($search) {
                $q2->where('title', 'like', "%$search%");
            })
            ->orWhereHas('user', function ($q3) use ($search) {
                $q3->where('name', 'like', "%$search%");
            });
        });
    }

    $borrowedBooks = $query->get();

    return view('borrow.books', compact('borrowedBooks', 'showReturned', 'search'));
        

    }


    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
        ]);

        // Create Borrow Transaction
        $borrowTransaction = BorrowTransaction::create([
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
            'borrow_date' => now(),
            'due_date' => $request->due_date,
        ]);

        // Use relationship to create borrowed book
        $borrowTransaction->borrowedBooks()->create([
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
        ]);

        // Update Book Stock
        $book = Book::findOrFail($request->book_id);
        $book->available_copies -= $request->quantity;
        $book->save();

        return redirect()->route('borrow.index')->with('success', 'Book borrowed successfully!');
    }

   public function returnBook($id)
{
    $transaction = BorrowTransaction::with('borrowedBooks.book')->findOrFail($id);

    if (!in_array($transaction->status, ['borrowed', 'overdue'])) {
        return back()->with('error', 'This book cannot be returned.');
    }

    $dueDate = Carbon::parse($transaction->due_date);
    $now = now();

    // Apply flat overdue fine if overdue
    if ($now->gt($dueDate)) {
        $transaction->fine_amount = self::FINE_OVERDUE;
        $transaction->fine_status = 'unpaid';
    }

    $transaction->status = 'returned';
    $transaction->save();

    // Restore the book quantities
    foreach ($transaction->borrowedBooks as $borrowed) {
        $borrowed->book->increment('available_copies', $borrowed->quantity);
    }

    return redirect()->route('borrow.index')->with('success', 'Book(s) returned successfully!');
}



public function markAsLost(Request $request, $id)
{
    $transaction = BorrowTransaction::with('borrowedBooks')->findOrFail($id);

    $request->validate([
        'lost_quantity' => 'required|integer|min:1',
    ]);

    $borrowed = $transaction->borrowedBooks->first();
    $totalBorrowed = $borrowed->quantity;

    if ($request->lost_quantity > $totalBorrowed) {
        return back()->with('error', 'Lost quantity cannot exceed borrowed quantity.');
    }

    $lostQty = $request->lost_quantity;
    $fine = $lostQty * self::FINE_LOST;

    $transaction->status = 'lost';
    $transaction->fine_amount = $fine;
    $transaction->fine_status = 'unpaid';
    $transaction->save();

    // Adjust available copies properly (reduce only by lost quantity)
    $borrowed->book->decrement('available_copies', $lostQty);

    return redirect()->route('borrow.index')->with('success', "Marked $lostQty book(s) as lost. Fine: ₱$fine");
}

public function markAsDamaged(Request $request, $id)
{
    $transaction = BorrowTransaction::with('borrowedBooks')->findOrFail($id);

    $borrowed = $transaction->borrowedBooks->first(); // assuming one book per transaction
    $totalBorrowed = $borrowed->quantity;

    if ($totalBorrowed == 1) {
        $damagedQty = 1;
    } else {
        $request->validate([
            'damaged_quantity' => 'required|integer|min:1|max:' . $totalBorrowed,
        ]);
        $damagedQty = $request->damaged_quantity;
    }

    $fine = $damagedQty * self::FINE_DAMAGED;

    $transaction->status = 'damaged';
    $transaction->fine_amount = $fine;
    $transaction->fine_status = 'unpaid';
    $transaction->save();

    // Update available copies accordingly, reduce by damaged quantity
    $borrowed->book->decrement('available_copies', $damagedQty);

    return redirect()->route('borrow.index')->with('success', "Marked $damagedQty book(s) as damaged. Fine: ₱$fine");
}

public function markFineAsPaid($id)
{
    $transaction = BorrowTransaction::findOrFail($id);

    if ($transaction->fine_amount > 0 && $transaction->fine_status === 'unpaid') {
        $transaction->fine_status = 'paid';
        $transaction->save();

        return back()->with('success', 'Fine marked as paid.');
    }

    return back()->with('error', 'No fine to mark as paid or already paid.');
}

public function borrow(Request $request, $id)
{
    $book = Book::findOrFail($id);

    // Check stock
    if ($book->available_copies < 1) {
        return redirect()->back()->with('error', 'No available copies to borrow.');
    }

    // Create Borrow Transaction
    $borrowTransaction = BorrowTransaction::create([
        'book_id' => $book->id,
        'user_id' => Auth::id(),
        'borrow_date' => now(),
        'due_date' => now()->addDays(7), // default due date
    ]);

    // Create Borrowed Book record
    $borrowTransaction->borrowedBooks()->create([
        'book_id' => $book->id,
        'user_id' => Auth::id(),
        'quantity' => 1,
    ]);

    // Update stock
    $book->available_copies -= 1;
    $book->save();

    return redirect()->route('borrow.index')->with('success', 'Book borrowed successfully!');
}




}

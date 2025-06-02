<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowTransaction;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   public function index()
{
    if (!Auth::check()) {
        return redirect()->back();
    }

    $user = Auth::user();
    $usertype = $user->usertype;

    // Admin dashboard
    if ($usertype === 'admin') {
        $totalBooks = Book::sum('quantity');
        $availableBooks = Book::sum('available_copies');
        $borrowedBooks = BorrowTransaction::where('status', 'borrowed')->count();
        $overdueBooks = BorrowTransaction::where('status', 'overdue')->count();
        $lostBooks = BorrowTransaction::where('status', 'lost')->count();
        $damagedBooks = BorrowTransaction::where('status', 'damaged')->count();
        $unpaidFines = BorrowTransaction::where('fine_amount', '>', 0)
            ->where('fine_status', 'unpaid')
            ->sum('fine_amount');

        return view('admin.index', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'overdueBooks',
            'lostBooks',
            'damagedBooks',
            'unpaidFines'
        ));
    }

    // User dashboard: fetch user-specific data
     $borrowedCount = BorrowTransaction::where('user_id', $user->id)
        ->whereIn('status', ['borrowed', 'overdue'])
        ->count();

    $OverDueCount = BorrowTransaction::where('user_id', $user->id)
        ->whereIn('status', ['overdue'])
        ->count();
    
    


    $unpaidFines = BorrowTransaction::where('user_id', $user->id)
        ->where('fine_amount', '>', 0)
        ->where('fine_status', 'unpaid')
        ->sum('fine_amount');

    return view('dashboard', compact('borrowedCount', 'unpaidFines', 'OverDueCount'));
}



   
}



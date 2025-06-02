<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Borrow Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-8">

                    <div x-data="{ open: false }">
                        <button @click="open = true"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm mb-4">
                            Borrow a Book
                        </button>

                        <!-- Modal -->
                        <div x-show="open"
                            class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50"
                            x-cloak>
                            <div @click.away="open = false"
                                class="bg-white w-full max-w-xl p-6 rounded-lg shadow-lg relative">
                                <button @click="open = false"
                                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg">&times;</button>

                                <h2 class="text-xl font-semibold text-gray-800 mb-4">Borrow a Book</h2>

                                @if(session('success'))
                                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('borrow.store') }}" method="POST" class="space-y-5">
                                    @csrf

                                    <div>
                                        <label for="book_id"
                                            class="block text-sm font-medium text-gray-700">Select Book</label>
                                        <select name="book_id" id="book_id" required
                                            class="w-full border border-gray-300 rounded px-3 py-2">
                                            <option value="">-- Select a Book --</option>
                                            @foreach($books as $book)
                                                <option value="{{ $book->id }}">
                                                    {{ $book->title }} (Available: {{ $book->available_copies }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if(Auth::user()->usertype === 'admin')
                                        <div>
                                            <label for="user_id" class="block text-sm font-medium text-gray-700">Borrower</label>
                                            <select name="user_id" id="user_id" required
                                                class="w-full border border-gray-300 rounded px-3 py-2">
                                                <option value="">-- Select a Borrower --</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Borrower</label>
                                            <input type="text" value="{{ Auth::user()->name }}" readonly
                                                class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-gray-700">
                                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                        </div>
                                    @endif

                                    <div>
                                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                        <input type="date" name="due_date" id="due_date"
                                            value="{{ old('due_date', $defaultDueDate) }}" required
                                            class="w-full border border-gray-300 rounded px-3 py-2">
                                    </div>

                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                        <input type="number" name="quantity" id="quantity" min="1" value="1"
                                            class="w-full border border-gray-300 rounded px-3 py-2" required>
                                    </div>

                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="open = false"
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                            Borrow Book
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Alpine for Lost Modal -->
                    <div x-data="{
                        showLostModal: false,
                        currentTransactionId: null,
                        maxLostQty: 1,
                        lostQty: 1,
                        submitLost() {
                            if (this.lostQty > this.maxLostQty) {
                                alert('Lost quantity cannot exceed borrowed quantity.');
                                return;
                            }
                            this.$refs.lostForm.action = `/borrow/${this.currentTransactionId}/lost`;
                            this.$refs.lostForm.submit();
                        },
                        markLost(transactionId, qty) {
                            if (qty === 1) {
                                this.currentTransactionId = transactionId;
                                this.lostQty = 1;
                                this.$refs.autoLostQty.value = 1;
                                this.$refs.autoLostForm.action = `/borrow/${transactionId}/lost`;
                                this.$refs.autoLostForm.submit();
                            } else {
                                this.currentTransactionId = transactionId;
                                this.maxLostQty = qty;
                                this.lostQty = 1;
                                this.showLostModal = true;
                            }
                        },

                        showDamagedModal: false,
                        maxDamagedQty: 1,
                        damagedQty: 1,
                        submitDamaged() {
                            if (this.damagedQty > this.maxDamagedQty) {
                                alert('Damaged quantity cannot exceed borrowed quantity.');
                                return;
                            }
                            this.$refs.damagedForm.action = `/borrow/${this.currentTransactionId}/damaged`;
                            this.$refs.damagedForm.submit();
                        },
                        markDamaged(transactionId, qty) {
                            if (qty === 1) {
                                this.currentTransactionId = transactionId;
                                this.damagedQty = 1;
                                this.$refs.autoDamagedQty.value = 1;
                                this.$refs.autoDamagedForm.action = `/borrow/${transactionId}/damaged`;
                                this.$refs.autoDamagedForm.submit();
                            } else {
                                this.currentTransactionId = transactionId;
                                this.maxDamagedQty = qty;
                                this.damagedQty = 1;
                                this.showDamagedModal = true;
                            }
                        }
                    }">

                        <form method="POST" x-ref="autoLostForm" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="lost_quantity" x-ref="autoLostQty" value="1" />
                        </form>

                        <form method="POST" x-ref="autoDamagedForm" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="damaged_quantity" x-ref="autoDamagedQty" value="1" />
                        </form>

                        <table class="min-w-full table-auto text-sm text-left bg-white shadow rounded">
                            <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                                <tr>
                                    <th class="px-4 py-2">Book Title</th>
                                    @if(Auth::user()->usertype !== 'user')
                                        <th class="px-4 py-2">Student Name</th>
                                    @endif
                                    <th class="px-4 py-2">Due Date</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Fine Status</th>
                                    @if(Auth::user()->usertype !== 'user')
                                        <th class="px-4 py-2">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-800">
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td class="px-4 py-2">{{ $transaction->book->title }}</td>
                                        @if(Auth::user()->usertype === 'admin')
                                            <td class="px-4 py-2">{{ $transaction->user->name }}</td>
                                        @endif
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($transaction->due_date)->format('m/d/Y') }}</td>
                                        <td class="px-4 py-2">
                                            @php
                                                $statusClasses = [
                                                    'borrowed' => 'bg-yellow-400 text-yellow-900',
                                                    'overdue' => 'bg-red-500 text-white',
                                                    'returned' => 'bg-green-500 text-white',
                                                    'lost' => 'bg-red-600 text-white',
                                                    'damaged' => 'bg-gray-500 text-white',
                                                ];
                                            @endphp
                                            <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $statusClasses[$transaction->status] ?? '' }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            @if (in_array($transaction->status, ['borrowed', 'returned']))
                                                <span class="inline-block bg-gray-400 text-white text-xs px-2 py-1 rounded">No Fine</span>
                                            @elseif ($transaction->fine_status === 'paid')
                                                <span class="inline-block bg-green-500 text-white text-xs px-2 py-1 rounded">Paid</span>
                                            @elseif ($transaction->fine_status === 'unpaid')
                                                <span class="inline-block bg-red-500 text-white text-xs px-2 py-1 rounded">
                                                    Unpaid (₱{{ number_format($transaction->fine_amount, 2) }})
                                                </span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->usertype === 'admin')
                                            <td class="px-4 py-2 space-x-1">
                                                @if ($transaction->status === 'borrowed'|| 'overdue')
                                                    <form action="{{ route('borrow.return', $transaction->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700">Return</button>
                                                    </form>

                                                    <button @click.prevent="markLost({{ $transaction->id }}, {{ $transaction->borrowedBooks->first()->quantity ?? 1 }})"
                                                        class="bg-red-600 text-white text-xs px-3 py-1 rounded hover:bg-red-700">
                                                        Mark as Lost
                                                    </button>

                                                    <button @click.prevent="markDamaged({{ $transaction->id }}, {{ $transaction->borrowedBooks->first()->quantity ?? 1 }})"
                                                        class="bg-yellow-600 text-white text-xs px-3 py-1 rounded hover:bg-yellow-700">
                                                        Mark as Damaged
                                                    </button>
                                                @endif

                                                @if ($transaction->fine_amount > 0 && $transaction->fine_status === 'unpaid')
                                                    <form action="{{ route('fines.markPaid', $transaction->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                                                            Mark Fine as Paid
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-gray-500">No borrow transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Lost Modal -->
                        <div x-show="showLostModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div @click.away="showLostModal = false" class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">Mark Lost</h3>
                                <form x-ref="lostForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="lost_quantity" class="block mb-1 font-medium">Quantity Lost</label>
                                        <input type="number" id="lost_quantity" name="lost_quantity" min="1" 
                                            :max="maxLostQty" x-model.number="lostQty" required
                                            class="w-full border border-gray-300 rounded px-3 py-2" />
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="showLostModal = false"
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                                        <button type="button" @click="submitLost()"
                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Damaged Modal -->
                        <div x-show="showDamagedModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div @click.away="showDamagedModal = false" class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                                <h3 class="text-lg font-semibold mb-4">Mark Damaged</h3>
                                <form x-ref="damagedForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="damaged_quantity" class="block mb-1 font-medium">Quantity Damaged</label>
                                        <input type="number" id="damaged_quantity" name="damaged_quantity" min="1" 
                                            :max="maxDamagedQty" x-model.number="damagedQty" required
                                            class="w-full border border-gray-300 rounded px-3 py-2" />
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="showDamagedModal = false"
                                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                                        <button type="button" @click="submitDamaged()"
                                            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

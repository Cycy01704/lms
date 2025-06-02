<?php

namespace App\Console\Commands;

use App\Models\BorrowTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkOverdueBooks extends Command
{
    protected $signature = 'mark:overdue-books';
    protected $description = 'Mark books as overdue if past due date and not returned';

    public function handle()
    {
        $today = Carbon::today();

        BorrowTransaction::where('status', 'borrowed')
            ->whereDate('due_date', '<', $today)
            ->update(['status' => 'overdue']);

        $this->info('Overdue books updated successfully.');
    }

    
}

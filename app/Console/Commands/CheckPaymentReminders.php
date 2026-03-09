<?php
// app/Console/Commands/CheckPaymentReminders.php

namespace App\Console\Commands;

use App\Models\RehabPaymentInstallment;
use App\Models\RehabOrder;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckPaymentReminders extends Command
{
    protected $signature = 'payments:check-reminders';
    protected $description = 'Check for upcoming and overdue payments';

    public function handle()
    {
        $this->info('Checking payment reminders...');

        // Mark overdue installments
        $overdueInstallments = RehabPaymentInstallment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        // Update order payment status for overdue
        $overdueOrders = RehabOrder::whereHas('paymentInstallments', function ($query) {
            $query->where('status', 'overdue');
        })->where('payment_status', '!=', 'paid')
          ->update(['payment_status' => 'overdue']);

        // Check for upcoming payments (5 days before)
        $upcomingDate = now()->addDays(5);
        $upcomingInstallments = RehabPaymentInstallment::with('rehabOrder')
            ->where('status', 'pending')
            ->whereDate('due_date', '<=', $upcomingDate)
            ->whereDate('due_date', '>=', now())
            ->get();

        foreach ($upcomingInstallments as $installment) {
            // You could send notifications here
            $daysLeft = now()->diffInDays($installment->due_date, false);
            
            $this->info("Reminder: Payment of {$installment->amount} due in {$daysLeft} days for Order #{$installment->rehab_order_id}");
            
            // Mark that reminder was sent
            if ($installment->rehabOrder) {
                $installment->rehabOrder->payment_reminder_sent = true;
                $installment->rehabOrder->save();
            }
        }

        $this->info('Payment reminders check completed.');
        
        return Command::SUCCESS;
    }
}
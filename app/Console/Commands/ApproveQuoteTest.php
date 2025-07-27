<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use App\Models\User;

class ApproveQuoteTest extends Command
{
    protected $signature = 'test:approve-quote {quote_id}';
    protected $description = 'Test quote approval and notification system';

    public function handle()
    {
        $quoteId = $this->argument('quote_id');
        
        $quote = Quote::with(['project', 'enquiry', 'lineItems'])->find($quoteId);
        if (!$quote) {
            $this->error("Quote #{$quoteId} not found.");
            return 1;
        }

        $this->info("🔍 Quote Details:");
        $this->info("   ID: #{$quote->id}");
        $this->info("   Customer: {$quote->customer_name}");
        $this->info("   Current Status: {$quote->status}");
        $this->info("   Project: " . ($quote->project ? $quote->project->name : ($quote->enquiry ? $quote->enquiry->project_name : 'N/A')));
        $this->newLine();

        if ($quote->status === 'approved') {
            $this->warn("⚠️  Quote is already approved!");
            $this->info("   Approved at: " . ($quote->approved_at ? $quote->approved_at->format('M d, Y H:i') : 'N/A'));
            $this->info("   Approved by: " . ($quote->approved_by ?? 'N/A'));
            return 0;
        }

        if (!$this->confirm('Do you want to approve this quote and send notifications?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->info('🔄 Approving quote and sending notifications...');
        
        try {
            $notificationCount = $quote->approveAndNotify();
            
            $this->info('✅ Quote approved successfully!');
            $this->info("📧 Notifications sent to {$notificationCount} users");
            $this->info("📅 Approved at: " . $quote->fresh()->approved_at->format('M d, Y H:i'));
            $this->info("👤 Approved by: " . $quote->fresh()->approved_by);
            
            // Show some notification details
            $this->newLine();
            $this->info('📊 Notification Summary:');
            $totalUsers = User::whereNotNull('email')->count();
            $this->info("   Total users with email: {$totalUsers}");
            $this->info("   Notifications sent: {$notificationCount}");
            $this->info("   Success rate: " . round(($notificationCount / $totalUsers) * 100, 1) . "%");
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to approve quote: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
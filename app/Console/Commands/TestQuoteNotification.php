<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use App\Models\User;
use App\Notifications\QuoteApprovedNotification;

class TestQuoteNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:quote-notification {quote_id?} {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the quote approval notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Quote Approval Notification System...');
        $this->newLine();

        // Get quote ID from argument or ask user
        $quoteId = $this->argument('quote_id');
        if (!$quoteId) {
            $quotes = Quote::with(['project', 'enquiry'])->latest()->take(10)->get();
            if ($quotes->isEmpty()) {
                $this->error('❌ No quotes found in the system. Please create a quote first.');
                return 1;
            }

            $this->table(['ID', 'Customer', 'Project/Enquiry', 'Status', 'Created'], 
                $quotes->map(function($quote) {
                    return [
                        $quote->id,
                        $quote->customer_name,
                        $quote->project ? $quote->project->name : ($quote->enquiry ? $quote->enquiry->project_name : 'N/A'),
                        $quote->status,
                        $quote->created_at->format('M d, Y')
                    ];
                })->toArray()
            );

            $quoteId = $this->ask('Enter the Quote ID to test');
        }

        // Find the quote
        $quote = Quote::with(['project', 'enquiry', 'lineItems'])->find($quoteId);
        if (!$quote) {
            $this->error("❌ Quote with ID {$quoteId} not found.");
            return 1;
        }

        $this->info("📋 Testing with Quote #{$quote->id}");
        $this->info("   Customer: {$quote->customer_name}");
        $this->info("   Status: {$quote->status}");
        $this->newLine();

        // Get user ID from argument or ask user
        $userId = $this->argument('user_id');
        if (!$userId) {
            $users = User::whereNotNull('email')->latest()->take(5)->get();
            if ($users->isEmpty()) {
                $this->error('❌ No users with email found in the system.');
                return 1;
            }

            $this->table(['ID', 'Name', 'Email', 'Role'], 
                $users->map(function($user) {
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role ?? 'N/A'
                    ];
                })->toArray()
            );

            $userId = $this->ask('Enter the User ID to test notification (or press Enter to test all users)', 'all');
        }

        // Test notification
        if ($userId === 'all') {
            $this->info('🔄 Testing notification for all users...');
            $users = User::whereNotNull('email')->get();
            $successCount = 0;
            $errorCount = 0;

            foreach ($users as $user) {
                try {
                    $user->notify(new QuoteApprovedNotification($quote));
                    $this->info("   ✅ Notification sent to: {$user->name} ({$user->email})");
                    $successCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to send to {$user->name}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            $this->newLine();
            $this->info("📊 Results:");
            $this->info("   ✅ Successful: {$successCount}");
            $this->info("   ❌ Failed: {$errorCount}");
            $this->info("   📧 Total users: " . $users->count());

        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ User with ID {$userId} not found.");
                return 1;
            }

            $this->info("🔄 Testing notification for: {$user->name} ({$user->email})");

            try {
                $user->notify(new QuoteApprovedNotification($quote));
                $this->info("✅ Notification sent successfully!");
                
                // Check if notification was stored in database
                $dbNotification = $user->notifications()->latest()->first();
                if ($dbNotification) {
                    $this->info("✅ Database notification created:");
                    $this->info("   ID: {$dbNotification->id}");
                    $this->info("   Type: {$dbNotification->type}");
                    $this->info("   Created: {$dbNotification->created_at}");
                } else {
                    $this->warn("⚠️  Database notification not found (check database channel)");
                }

            } catch (\Exception $e) {
                $this->error("❌ Notification failed: " . $e->getMessage());
                $this->error("   Stack trace: " . $e->getTraceAsString());
                return 1;
            }
        }

        $this->newLine();
        $this->info('🎉 Test completed! Check your email and database for notifications.');
        
        // Show some helpful information
        $this->newLine();
        $this->info('📝 Troubleshooting Tips:');
        $this->info('   • Check mail configuration in .env file');
        $this->info('   • Verify MAIL_MAILER, MAIL_HOST, MAIL_PORT settings');
        $this->info('   • Check Laravel logs: storage/logs/laravel.log');
        $this->info('   • Verify notifications table exists: php artisan migrate');
        $this->info('   • Check queue workers if using queues: php artisan queue:work');

        return 0;
    }
}
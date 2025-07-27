<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteApprovedNotification extends Notification
{
    use Queueable;

    protected $quote;

    /**
     * Create a new notification instance.
     */
    public function __construct($quote)
    {
        $this->quote = $quote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            // Load relationships to avoid N+1 queries
            $this->quote->load(['project', 'enquiry', 'lineItems']);
            
            $projectName = $this->quote->project ? $this->quote->project->name : 
                          ($this->quote->enquiry ? $this->quote->enquiry->project_name : 'Unknown Project');
            
            $quoteTotal = $this->quote->lineItems->sum('quote_price') ?? 0;
            $formattedTotal = number_format($quoteTotal, 2);
            
            $viewUrl = $this->quote->project_id 
                ? route('quotes.show', ['project' => $this->quote->project_id, 'quote' => $this->quote->id])
                : route('enquiries.quotes.show', ['enquiry' => $this->quote->enquiry_id, 'quote' => $this->quote->id]);

            $approvedDate = $this->quote->approved_at ? $this->quote->approved_at->format('M d, Y \a\t H:i') : now()->format('M d, Y \a\t H:i');

            // Get project ID for display
            $projectId = $this->quote->project_id ?? ($this->quote->enquiry_id ? 'ENQ-' . $this->quote->enquiry_id : 'N/A');
            $projectReference = $this->quote->project ? $this->quote->project->project_id : ($this->quote->enquiry ? 'Enquiry #' . $this->quote->enquiry_id : 'N/A');

            return (new MailMessage)
                ->subject('Quote #' . $this->quote->id . ' Approved - ' . $projectName . ' (Project ID: ' . $projectReference . ')')
                ->greeting('Hello ' . ($notifiable->name ?? 'Team Member') . ',')
                ->line('Great news! A quote has been approved and is ready for processing.')
                ->line('**Quote Details:**')
                ->line('• Quote ID: #' . $this->quote->id)
                ->line('• Project ID: ' . $projectReference)
                ->line('• Project Name: ' . $projectName)
                ->line('• Customer: ' . $this->quote->customer_name)
                ->line('• Quote Total: KES ' . $formattedTotal)
                ->line('• Approved Date: ' . $approvedDate)
                ->line('• Approved By: ' . ($this->quote->approved_by ?? 'System'))
                ->action('View Quote Details', $viewUrl)
                ->line('Please take the necessary next steps to proceed with this approved quote.')
                ->salutation('Best regards, ' . config('app.name') . ' Team');
        } catch (\Exception $e) {
            \Log::error('Error generating quote approval email', [
                'quote_id' => $this->quote->id,
                'notifiable_id' => $notifiable->id,
                'error' => $e->getMessage()
            ]);
            
            // Fallback simple email
            return (new MailMessage)
                ->subject('Quote #' . $this->quote->id . ' Approved')
                ->line('A quote has been approved.')
                ->line('Quote ID: #' . $this->quote->id)
                ->line('Customer: ' . $this->quote->customer_name)
                ->line('Please check the system for more details.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        try {
            // Load relationships to avoid N+1 queries
            $this->quote->load(['project', 'enquiry', 'lineItems']);
            
            $projectName = $this->quote->project ? $this->quote->project->name : 
                          ($this->quote->enquiry ? $this->quote->enquiry->project_name : 'Unknown Project');
            
            $quoteTotal = $this->quote->lineItems->sum('quote_price') ?? 0;
            
            $viewUrl = $this->quote->project_id 
                ? route('quotes.show', ['project' => $this->quote->project_id, 'quote' => $this->quote->id])
                : route('enquiries.quotes.show', ['enquiry' => $this->quote->enquiry_id, 'quote' => $this->quote->id]);

            $approvedAt = $this->quote->approved_at ? $this->quote->approved_at->toISOString() : now()->toISOString();

            // Get project reference for database notification
            $projectReference = $this->quote->project ? $this->quote->project->project_id : ($this->quote->enquiry ? 'Enquiry #' . $this->quote->enquiry_id : 'N/A');

            return [
                'title' => 'Quote #' . $this->quote->id . ' Approved',
                'message' => 'Quote for ' . $projectName . ' (Project ID: ' . $projectReference . ') has been approved',
                'quote_id' => $this->quote->id,
                'project_id' => $this->quote->project_id,
                'enquiry_id' => $this->quote->enquiry_id,
                'project_reference' => $projectReference,
                'project_name' => $projectName,
                'customer_name' => $this->quote->customer_name,
                'quote_total' => $quoteTotal,
                'formatted_total' => 'KES ' . number_format($quoteTotal, 2),
                'status' => $this->quote->status,
                'approved_at' => $approvedAt,
                'approved_by' => $this->quote->approved_by ?? 'System',
                'view_url' => $viewUrl,
                'type' => 'quote_approved',
                'icon' => 'bi-check-circle-fill',
                'color' => 'success'
            ];
        } catch (\Exception $e) {
            \Log::error('Error generating quote approval database notification', [
                'quote_id' => $this->quote->id,
                'notifiable_id' => $notifiable->id,
                'error' => $e->getMessage()
            ]);
            
            // Fallback simple notification data
            return [
                'title' => 'Quote #' . $this->quote->id . ' Approved',
                'message' => 'A quote has been approved',
                'quote_id' => $this->quote->id,
                'customer_name' => $this->quote->customer_name,
                'status' => $this->quote->status,
                'type' => 'quote_approved',
                'icon' => 'bi-check-circle-fill',
                'color' => 'success'
            ];
        }
    }
}

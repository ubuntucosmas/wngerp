<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    const STATUS_PREPARED = 'prepared';
    const STATUS_WAITING_APPROVAL = 'waiting_approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'project_id',
        'enquiry_id',
        'customer_name',
        'project_budget_id',
        'customer_location',
        'attention',
        'quote_date',
        'project_start_date',
        'reference',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'project_start_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(QuoteLineItem::class);
    }
    
    public function projectBudget()
    {
        return $this->belongsTo(ProjectBudget::class);
    }

    public function approveAndNotify()
    {
        // Update quote status
        $this->status = self::STATUS_APPROVED;
        $this->approved_at = now();
        $this->approved_by = auth()->user()->name ?? 'System';
        $this->save();
        
        // Log the approval
        \Log::info('Quote approved', [
            'quote_id' => $this->id,
            'project_id' => $this->project_id,
            'enquiry_id' => $this->enquiry_id,
            'customer_name' => $this->customer_name,
            'approved_by' => auth()->user()->name ?? 'System',
            'approved_at' => now()->toISOString()
        ]);
        
        // Get all active users for notification
        $users = \App\Models\User::whereNotNull('email')->get();
        $notificationCount = 0;
        
        foreach ($users as $user) {
            try {
                $user->notify(new \App\Notifications\QuoteApprovedNotification($this));
                $notificationCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send quote approval notification', [
                    'quote_id' => $this->id,
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        \Log::info('Quote approval notifications sent', [
            'quote_id' => $this->id,
            'total_users' => $users->count(),
            'notifications_sent' => $notificationCount
        ]);
        
        return $notificationCount;
    }
}

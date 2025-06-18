<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'expected_delivery_date',
        'client_name',
        'project_name',
        'project_deliverables',
        'contact_person',
        'status',
        'assigned_po',
        'follow_up_notes',
        'project_id',
        'enquiry_number',
    ];

   protected $casts = [
        'date_received' => 'date:Y-m-d',
        'expected_delivery_date' => 'date:Y-m-d',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enquiry) {
            // Get the current year and month
            $year = date('y');
            $month = date('m');
            
            // Get the last enquiry number for this month
            $lastEnquiry = static::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', date('m'))
                ->orderBy('enquiry_number', 'desc')
                ->first();
            
            // Set the new enquiry number
            $enquiry->enquiry_number = $lastEnquiry ? $lastEnquiry->enquiry_number + 1 : 1;
        });
    }

    public function getFormattedIdAttribute()
    {
        $year = $this->created_at->format('y');
        $month = $this->created_at->format('m');
        $number = str_pad($this->enquiry_number, 3, '0', STR_PAD_LEFT);
        
        return "WNG/IQ/{$year}/{$month}/{$number}";
    }

    public function getDateReceivedAttribute($value)
    {
        return $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function getExpectedDeliveryDateAttribute($value)
    {
        return $value ? date('Y-m-d', strtotime($value)) : null;
    }
}
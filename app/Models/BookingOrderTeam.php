<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingOrderTeam extends Model
{
    protected $fillable = ['booking_order_id', 'team_type', 'member_name'];

    public function bookingOrder()
    {
        return $this->belongsTo(BookingOrder::class);
    }
}


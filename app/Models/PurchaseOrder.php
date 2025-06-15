<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'item_name', 'quantity', 'price']; // Mass-assignable fields

    // Define relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
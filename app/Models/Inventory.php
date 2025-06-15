<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;
        // Define relationship with Checkouts
    public function checkouts()
    {
        return $this->hasMany(Checkouts::class, 'inventory_id');
    }
    

    // Specify the table name
    protected $table = 'inventory';
    

    // Add fillable properties
    protected $fillable = [
        'sku', 'item_name', 'category_id', 'unit_of_measure', 
        'stock_on_hand', 'quantity_checked_in', 'quantity_checked_out',
        'returns', 'supplier', 'unit_price', 'total_value', 'order_date'
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

     // Specify the table name
    protected $table = 'categories';

    // Specify which fields are mass assignable
    protected $fillable = ['category_name'];

    // Define the relationship with the Inventory model
    public function inventory()
    {
        return $this->hasMany(Inventory::class); // A category can have many inventory items
    }
}
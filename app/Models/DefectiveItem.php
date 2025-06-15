<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectiveItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'item_name',
        'quantity',
        'defect_type',
        'reported_by',
        'date_reported',
        'remarks',
        'status',
    ];
}
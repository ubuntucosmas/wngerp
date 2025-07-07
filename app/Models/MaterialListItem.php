<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_list_id',
        'category',
        'item_name',
        'particular',
        'unit',
        'quantity',
        'comment',
    ];

    protected $casts = [
        'quantity' => 'float',
    ];

    public function materialList()
    {
        return $this->belongsTo(MaterialList::class);
    }
}

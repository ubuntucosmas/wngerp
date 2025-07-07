<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MaterialList;
use App\Models\ProductionParticular;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionItem extends Model
{

    use HasFactory;

    protected $fillable = [
        'material_list_id',
        'item_name',
        'template_id',
    ];
    public function materialList()
    {
        return $this->belongsTo(MaterialList::class);
    }

    public function particulars()
    {
        return $this->hasMany(ProductionParticular::class);
    }

    public function template()
    {
        return $this->belongsTo(ItemTemplate::class, 'template_id');
    }

}

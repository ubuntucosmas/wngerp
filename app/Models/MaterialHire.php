<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialHire extends Model
{
    public function materialList()
    {
        return $this->belongsTo(MaterialList::class);
    }
}

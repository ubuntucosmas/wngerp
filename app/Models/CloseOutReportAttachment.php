<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloseOutReportAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'close_out_report_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    public function report()
    {
        return $this->belongsTo(CloseOutReport::class, 'close_out_report_id');
    }
}



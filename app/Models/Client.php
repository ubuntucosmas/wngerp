<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $primaryKey = 'ClientID';
    
    protected $fillable = [
        'FullName',
        'ContactPerson',
        'Email',
        'Phone',
        'AltContact',
        'Address',
        'City',
        'County',
        'PostalAddress',
        'CustomerType',
        'LeadSource',
        'PreferredContact',
        'Industry',
        'CreatedBy',
    ];

    protected $casts = [
        'CustomerType' => 'string',
        'LeadSource' => 'string',
        'PreferredContact' => 'string',
        'CreatedAt' => 'datetime',
    ];
}

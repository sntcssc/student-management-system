<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'payment_method',
        'status',
        'transaction_date',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'type' => 'string',
        'payment_method' => 'string',
        'status' => 'string',
        'transaction_date' => 'datetime',
    ];
}

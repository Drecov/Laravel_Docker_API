<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    protected $fillable = [
        'account_id',
        'balance',
    ];

    protected $casts = [
        'account_id' => 'integer',
        'balance' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

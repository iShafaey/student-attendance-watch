<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;
    protected $casts = ['date' => 'datetime'];
    protected $fillable = [
        'type',
        'debtor',
        'creditor',
        'amount',
        'date',
    ];
}

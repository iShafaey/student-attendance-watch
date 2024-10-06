<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['student_name', 'father_name', 'phone_number', 'country_code', 'join_date', 'fees', 'status'];
}

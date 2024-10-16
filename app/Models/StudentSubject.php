<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title'];

    public function student_class(){
        return $this->hasOne(StudentClass::class, 'id', 'class_id');
    }
}

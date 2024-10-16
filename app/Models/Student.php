<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['student_name', 'father_name', 'phone_number', 'country_code', 'join_date', 'fees', 'status', 'age', 'class', 'student_code'];

    public function student_class() {
        return $this->hasOne(StudentClass::class, 'id', 'class');
    }

    protected function studentFullName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->student_name . '' . $this->father_name,
        );
    }

   public function fullName() {
       return $this->student_name . ' ' . $this->father_name;
   }
}

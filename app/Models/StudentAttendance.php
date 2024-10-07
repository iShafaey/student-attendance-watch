<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'attendance_datetime', 'phone_number', 'status'];

    protected function attendanceDatetime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => [
                'datetime' => Carbon::parse($value)->format('Y-m-d H:i A'),
                'date' => Carbon::parse($value)->format('Y-m-d'),
                'time' => Carbon::parse($value)->format('H:i A')
            ],
        );
    }

    public function student() {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;
    protected $casts = [
        'attendance_in_datetime' => 'datetime',
        'attendance_out_datetime' => 'datetime',
        'absence_datetime' => 'datetime',
    ];

    protected $fillable = [
        'student_id',
        'attendance_datetime',
        'expenses_datetime',
        'exam_result_datetime',
        'expenses_value',
        'exam_result',
        'phone_number',
        'status',
        'attendance_in_datetime',
        'attendance_out_datetime'
    ];

//    protected function attendanceDatetime(): Attribute
//    {
//        return Attribute::make(
//            get: fn (string $value) => [
//                'datetime' => Carbon::parse($value)->format('Y-m-d H:i A'),
//                'date' => Carbon::parse($value)->format('Y-m-d'),
//                'time' => Carbon::parse($value)->format('H:i A')
//            ],
//        );
//    }

    public function student(){
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Carbon::setLocale('ar');
        $name = $this?->student?->student_name ?? null;

        if (!is_null($this->attendance_in_datetime) && is_null($this->attendance_out_datetime)){
            $timeIn = $this->attendance_in_datetime->format('h:i A');
            $template = option('attendance_in_message');
            $message = str_replace(['{name}', '{time}'], [$name, $timeIn], $template);
            $type = "attendance_in";
        } elseif (!is_null($this->attendance_out_datetime)){
            $timeOut = $this->attendance_out_datetime->format('h:i A');
            $template = option('attendance_out_message');
            $message = str_replace(['{name}', '{time}'], [$name, $timeOut], $template);
            $type = "attendance_out";
        } elseif (!is_null($this->absence_datetime)){
            $template = option('attendance_absence_message');
            $message = str_replace(['{name}'], [$name], $template);
            $type = "absence";
        } elseif (!is_null($this->expenses_datetime)){
            $value = $this->expenses_value;
            $template = option('expenses_message');
            $message = str_replace(['{name}', '{value}'], [$name, $value], $template);
            $type = "expenses";
        } elseif (!is_null($this->expenses_reminder_datetime)){
            $value = $this->expenses_value;
            $template = option('expenses_reminder_message');
            $message = str_replace(['{name}', '{value}'], [$name, $value], $template);
            $type = "expenses_reminder";
        } else {
            $exam_results = $this->exam_result;
            $template = option('exam_message');
            $message = str_replace(['{name}', '{exam_results}'], [$name, $exam_results], $template);
            $type = "exam";
        }

        return [
            'phone_number' => $this->phone_number,
            'name' => $name,
            'message' => $message,
            'type' => $type
        ];
    }
}

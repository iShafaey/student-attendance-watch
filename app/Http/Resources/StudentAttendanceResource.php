<?php

namespace App\Http\Resources;

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
        $name = $this?->student?->student_name ?? null;

        if (!is_null($this->attendance_datetime)){
            $template = option('attendance_message');
            $message = str_replace(['{name}'], [$name], $template);
            $type = "attendance";
        } elseif (!is_null($this->expenses_datetime)){
            $value = $this->expenses_value;
            $template = option('expenses_message');
            $message = str_replace(['{name}', '{value}'], [$name, $value], $template);
            $type = "expenses";
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

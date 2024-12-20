<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\StudentAttendance;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;

class ApiController extends Controller {
    public function getNumbers() {
//        $student_attendance = StudentRecord::whereIn('status', ['pending', 'failed'])
//            ->orderByRaw("FIELD(status, 'pending') DESC")
//            ->get();

        $student_attendance = StudentRecord::whereIn('status', ['pending', 'failed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $contacts = StudentAttendanceResource::collection($student_attendance)->resolve();

        return Response::json([
            'delay_min' => option('delay_min'),
            'delay_max' => option('delay_max'),
            'contacts' => $contacts
        ], 200);
    }

    public function updateStatus(Request $request) {
        $statusMapping = [
            'message_sent' => 'sent',
            'message_failed' => 'failed',
            'number_blacklisted' => 'blacklist',
        ];

        if (!array_key_exists($request->status, $statusMapping)) {
            return Response::json(['status' => 'fail'], 400);
        }

        $datetimeField = match ($request->type) {
            'attendance_in' => 'attendance_in_datetime',
            'attendance_out' => 'attendance_out_datetime',
            'absence' => 'absence_datetime',
            'expenses' => 'expenses_datetime',
            'expenses_reminder' => 'expenses_reminder_datetime',
            'bulk_message' => 'bulk_message_datetime',
            'exam' => 'exam_result_datetime',
            default => null,
        };

        if ($datetimeField) {
            if ($request->status != 'number_blacklisted') {
                $studentRecord = StudentRecord::wherePhoneNumber($request->phone_number)
                    ->whereIn('status', ['pending', 'failed'])
                    ->first();
            } else {
                $studentRecord = StudentRecord::wherePhoneNumber($request->phone_number);
            }

            if ($studentRecord) {
                $studentRecord->update(['status' => $statusMapping[$request->status]]);
                return Response::json(['status' => 'success'], 200);
            }
        }

        return Response::json(['status' => 'fail'], 400);
    }

    public function getOptions() {
        return Response::json([
            'message' => option('message'),
            'delay_min' => (int)option('delay_min'),
            'delay_max' => (int)option('delay_max'),
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Response;

class ApiController extends Controller
{
    public function getNumbers() {
        $student_attendance = StudentAttendance::whereIn('status', ['pending'])->get();
        $contacts = StudentAttendanceResource::collection($student_attendance)->resolve();
        return Response::json([
            'message' => option('message'),
            'delay_min' => option('delay_min'),
            'delay_max' => option('delay_max'),
            'contacts' => $contacts
        ], 200);
    }

    public function updateStatus(Request $request) {
        $statusMapping = [
            'message_sent' => 'sent',
            'message_failed' => 'failed',
        ];

        if (array_key_exists($request->status, $statusMapping)) {
            StudentAttendance::wherePhoneNumber($request->phone_number)->update(['status' => $statusMapping[$request->status]]);
            return Response::json(['status' => 'success'], 200);
        } else {
            return Response::json(['status' => 'fail'], 400);
        }
    }

    public function getOptions() {
        return Response::json([
            'message' => option('message'),
            'delay_min' => (int)option('delay_min'),
            'delay_max' => (int)option('delay_max'),
        ], 200);
    }
}

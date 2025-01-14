<?php
function GenerateRandomCode($length = 5, $type = 'number', $uppercase = true, $prefix = false, $static = false) {
    // Number
    $numbers = mt_rand(0, mt_getrandmax());
    $shuffle = rand($numbers, $numbers);
    $digits = substr(str_shuffle(str_repeat($x = $shuffle, ceil($length / strlen($x)))), 1, $length);

    // Hash
    $hash = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);

    if ($prefix && $static && $type == 'number') {
        return str_pad($static, $length, '0', STR_PAD_LEFT);
    } else {
        $return = $type == 'number' ? $digits : GetAppID() . '_' . $hash;
        if ($uppercase) {
            return strtoupper($return);
        } else {
            return replacePrefixZero($return);
        }
    }
}

function ConvertArrayToText($data) {
    $subjects = $data['subject_title'];
    $degrees = $data['degree'];
    $result = [];

    foreach ($subjects as $index => $subject) {
        if ($degrees[$index]) {
            $result[] = "{$subject}: {$degrees[$index]}";
        }
    }

    return implode('، ', $result);
}

function replacePrefixZero($phoneNumber) {
    if (substr($phoneNumber, 0, 1) === "0") {
        $randomNumber = rand(1, 9);
        $phoneNumber = $randomNumber . substr($phoneNumber, 1);
    }
    return $phoneNumber;
}

function attendanceRole($class_id, $day_name) {
    try {
        $attendance_roles = Cache::get('attendance_roles', []);
        return $attendance_roles ? in_array($class_id, $attendance_roles[$day_name]) : false;
    } catch (\Throwable $th) {
        return false;
    }
}

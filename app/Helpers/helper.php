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
            return $return;
        }
    }
}

function ConvertArrayToText($data) {
    $subjects = $data['subject_title'];
    $degrees = $data['degree'];
    $result = [];

    foreach ($subjects as $index => $subject) {
        $result[] = "{$subject}: {$degrees[$index]}";
    }

    return implode('، ', $result);
}

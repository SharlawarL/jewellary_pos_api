<?php

function currentDateTime() {
    date_default_timezone_set("Asia/Kolkata");
    return date('Y-m-d H:i:s');
}

function currentDate() {
    date_default_timezone_set("Asia/Kolkata");
    return date('Y-m-d');
}

function jwtEncodeToken() {
    date_default_timezone_set("Asia/Kolkata");
    return date('Y-m-d');
}

function jwtDecodeToken($token , $key) {
    
    // print_r($key);die();
    $jwtToken_decode = JWT::decode($token, $key, array('HS256'));
    $decodedData = (array) $jwtToken_decode;
        
    return $decodedData;
}

?>
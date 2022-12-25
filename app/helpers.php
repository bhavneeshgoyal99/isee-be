<?php

function successResponse($msg = '', $data = [])
{
    $response = [
        'data' => $data,
        'success' => true,
        'msg' => $msg ? $msg : 'Successfull',
        'status' => 200
    ];

    return $response;
}

function failResponse($error = null, $data = [])
{
    $msg = env('APP_DEBUG') ? (is_string($error) ? $error : $error->getMessage()) : 'Internal Server Error';

    $response = [
        'data' => $data,
        'success' => false,
        'msg' => $msg,
        'status' => 401
    ];

    return $response;
}

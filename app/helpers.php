<?php
function successResponse($data, $statusCode, $msg, $options = [])
{
    if (count($options) == 0) {
        return response([
            "status" => "success",
            "message" => $msg,
            "data" => $data
        ], $statusCode);
    } else {
        $response = [
            "status" => "success",
            "message" => $msg,
            "data" => $data,
        ];
        foreach ($options as $key => $value) {
            $response[$key] = $value;
        }
        return response($response);
    }
}

function errorResponse($statusCode, $msg)
{
    return response()->json([
        'status' => 'error',
        'message' => $msg,
        'data' => []
    ], $statusCode);
}

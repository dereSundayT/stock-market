<?php
function successResponse($data, $statusCode, $msg)
{
    return response()->json([
        'status' => 'success',
        'message' => $msg,
        'data' => $data
    ], $statusCode);
}

function errorResponse($statusCode, $msg)
{
    return response()->json([
        'status' => 'error',
        'message' => $msg,
        'data' => []
    ], $statusCode);
}

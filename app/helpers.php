<?php
function successResponse($data, $statusCode, $msg)
{
    return response()->json([
        'status' => 'success',
        'message' => $msg,
        'data' => $data
    ], $statusCode);
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //
    public function handleResponse($result, $msg){
        $res = [
            'success' => true,
            'data'=> $result,
            'message' => $msg,
        ];
        return response()->json($res,200);
    }

    public function handleError($error,$status){
        $res = [
            'success' => false,
            'message'=> $error,
            'status' => $status

        ];
        return response()->json($res,$status);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\SSOController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ajaxController extends Controller
{
    public function user_detail($cid) {
        $sso = new SSOController;
        $data = $sso->ProfileData($cid);
        return response()->json(['data'=>json_encode($data, JSON_UNESCAPED_UNICODE)]);
    }
}

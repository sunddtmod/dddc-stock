<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CmsHelper as cms;

class frontendController extends Controller
{
    public function home($key='') {
        $query = DB::table("ref_parcel_group")->select("id","name")->where("status",1)->get();
        $group = cms::ObjArr($query);
        $area = cms::toArray("ref_area");

        $query = DB::table("parcel_detail")
        ->whereNull("deleted_at")
        ->where("balance", ">", 0)
        ->where("status", 1);
        if( $key != "" ) {
            $query->where('name', 'LIKE', '%'.$key.'%');
        }
        $data = $query->get();

        return view('frontend/home', [
            "key"=>$key,
            "group"=>$group,
            "area"=>$area,
            "data"=>$data
        ]);
    }
}

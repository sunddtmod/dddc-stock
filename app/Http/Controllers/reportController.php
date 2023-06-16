<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CmsHelper as cms;

class reportController extends Controller
{
    public function home() {
        return view('frontend/home', [
        ]);
    }

    public function parcel_in($fyear='') {
        $oldyear = 2566;
        $nowyear = date("Y",strtotime("+3 month",strtotime(date('y-m-d')))) + 543;
        $curryear = ( $fyear=='' ) ? $nowyear : $fyear;

        $data = DB::table("parcel_store_order")->where('fyear', $curryear)->get();

        return view('backend/report/parcel_in', [
            "oldyear" => $oldyear,
            "nowyear" =>$nowyear,
            "curryear" => $curryear,

            "data"=>$data
        ]);
    }
    public function parcel_in_ajax($id=0) {
        $data = [];

        $temp = DB::table("parcel_detail")->whereNull("deleted_at")->get();
        $parcel_detail = [];
        foreach($temp as $x=>$item) {
            $parcel_detail[$item->id] = [
                "parcel_id" => $item->parcel_id,
                "code" => $item->code,
                "name" => $item->name,
                "unit" => $item->unit
            ];
        }

        $parcel_store = DB::table("parcel_store")->where("id", $id)->get();
        $sum_price = 0;
        foreach($parcel_store as $item) {
            $id = $item->id;
            $parcel = $parcel_detail[$item->parcel_detail_id];
            $sum_price += ($item->price * $item->amount);

            $data[] = [
                "id" => $id,
                "code" => $parcel['parcel_id'].":".$parcel['code'],
                "name" => $parcel['name'],
                "unit" => $parcel['unit'],
                "amount" => $item->amount,
                "price" => number_format($item->price, 2),
                "sum" => number_format($item->price * $item->amount, 2)
            ];
        }
        return response()->json([
            'data'=>json_encode($data, JSON_UNESCAPED_UNICODE),
            'sum_price'=>number_format($sum_price, 2),
        ]);
    }
    
    public function parcel_out($fyear='') {
        $oldyear = 2566;
        $nowyear = date("Y",strtotime("+3 month",strtotime(date('Y-m-d')))) + 543;
        $curryear = ( $fyear=='' ) ? $nowyear : $fyear;

        $data = DB::table("withdraw")->where('fyear', $curryear)->get();

        return view('backend/report/parcel_out', [
            "oldyear" => $oldyear,
            "nowyear" =>$nowyear,
            "curryear" => $curryear,

            "data"=>$data
        ]);
    }
    public function parcel_out_ajax($id=0) {
        $data = [];

        $temp = DB::table("parcel_detail")->whereNull("deleted_at")->get();
        $parcel_detail = [];
        foreach($temp as $x=>$item) {
            $parcel_detail[$item->id] = [
                "parcel_id" => $item->parcel_id,
                "code" => $item->code,
                "name" => $item->name,
                "unit" => $item->unit
            ];
        }
        $withdraw_list = DB::table("withdraw_list")->where("id", $id)->get();
        $sum_price = 0;
        foreach($withdraw_list as $item) {
            $id = $item->id;
            $parcel = $parcel_detail[$item->parcel_detail_id];
            $sum_price += ($item->price * $item->amount);

            $data[] = [
                "id" => $id,
                "code" => $parcel['parcel_id'].":".$parcel['code'],
                "name" => $parcel['name'],
                "unit" => $parcel['unit'],
                "amount" => $item->amount,
                "price" => number_format($item->price, 2),
                "sum" => number_format($item->price * $item->amount, 2)
            ];


            <th>id</th>
            <th style="width: 60px;">รหัส</th>
            <th>ชื่อวัสดุ</th>
            <th style="width: 90px;">เบิก</th>
            <th style="width: 60px;">หน่วย</th>
            <th style="width: 90px;">ราคา (บาท)</th>
            <th style="width: 90px;">รวม (บาท)</th>


        }
        return response()->json([
            'data'=>json_encode($data, JSON_UNESCAPED_UNICODE),
            'sum_price'=>number_format($sum_price, 2),
        ]);
    }

    public function parcel_date($d1='',$d2='') {
        $d1 = ( $d1=='' ) ? date("Y-m")."-01" : $d1;
        $d2 = ( $d2=='' ) ? date("Y-m-d") : $d2;
        $data = DB::table("parcel_log")->whereBetween('created_at', [$d1, $d2])->get();

        $temp = DB::table("parcel_detail")->whereNull("deleted_at")->get();
        $parcel_detail = [];
        foreach($temp as $x=>$item) {
            $parcel_detail[$item->id] = [
                "code" => $item->parcel_id.":".$item->code,
                "name" => $item->name,
                "unit" => $item->unit
            ];
        }
        $parcel_store = cms::toArray("parcel_store", "parcel_detail_id", "price");

        return view('backend/report/parcel_date', [
            "data"=>$data,
            "parcel_detail" => $parcel_detail,
            "parcel_store" => $parcel_store
        ]);
    }

    public function parcel_person($name='') {
        if( $name=='' ) {
            return redirect()->back()->with(['Error'=>'บันทึกไม่สำเร็จ']);
        }
        $parcel_detail_list = cms::toArray("parcel_detail");

        // $data = DB::table("parcel_log")
        //     ->where('parcel_detail_id', $id)
        //     ->get();
        // $item = DB::table("parcel_detail")
        //     ->where('id', $id)
        //     ->whereNull("deleted_at")
        //     ->first();
        // $parcel_store = DB::table("parcel_store")
        //     ->where('parcel_detail_id', $id)
        //     ->first();

        // return view('backend/report/parcel_one', [
        //     "curr_id"=>$id,
        //     "parcel_detail_list" => $parcel_detail_list,

        //     "data"=>$data,
        //     "item" => $item,
        //     "parcel_store" => $parcel_store
        // ]);
    }

    public function parcel_one($id=1) {
        if( $id==0 ) {
            return redirect()->back()->with(['Error'=>'บันทึกไม่สำเร็จ']);
        }
        $parcel_detail_list = cms::toArray("parcel_detail");

        $data = DB::table("parcel_log")
            ->where('parcel_detail_id', $id)
            ->get();
        $item = DB::table("parcel_detail")
            ->where('id', $id)
            ->whereNull("deleted_at")
            ->first();
        $parcel_store = DB::table("parcel_store")
            ->where('parcel_detail_id', $id)
            ->first();

        return view('backend/report/parcel_one', [
            "curr_id"=>$id,
            "parcel_detail_list" => $parcel_detail_list,

            "data"=>$data,
            "item" => $item,
            "parcel_store" => $parcel_store
        ]);
    }

    public function balance() {
        $data = DB::table("parcel_store")
            ->selectRaw(" `parcel_detail_id`,`price`, sum(`balance`) as balance ")
            ->where("balance",">",0)
            ->groupBy("parcel_detail_id")
            ->groupBy("price")
            ->get();

        $temp = DB::table("parcel_detail")->whereNull("deleted_at")->get();
        $parcel_detail = [];
        foreach($temp as $x=>$item) {
            $parcel_detail[$item->id] = [
                "parcel_id" => $item->parcel_id,
                "name" => $item->name,
                "unit" => $item->unit
            ];
        }

        return view('backend/report/balance', [
            "data"=>$data,
            "parcel_detail" => $parcel_detail
        ]);
    }
}

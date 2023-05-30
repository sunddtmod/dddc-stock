<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class settingController extends Controller
{
    //-----------------------[ AREA ]----------------------
    public function area() {
        $data = DB::table("ref_area")->whereNull("deleted_at")->get();
        return view('backend/setting/area', [
            "data"=>$data
        ]);
    }
    public function area_add(Request $request) {
        try {
            $data = [];
            $data['name'] = $request->name;
            DB::table("ref_area")->insert($data);
            return redirect()->back()->with(['Success'=>"บันทึกสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'บันทึกไม่สำเร็จ']);
        }   
    }
    public function area_update($id, Request $request) {
        try {
            $data = [];
            $data['name'] = $request->name;
            DB::table("ref_area")->where('id', $id)->update($data);
            return redirect()->back()->with(['Success'=>"อัพเดทสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'อัพเดทไม่สำเร็จ']);
        }  
    }
    public function area_delete($id) {
        try {
            DB::table("ref_area")->where('id', $id)->update([
                'deleted_at'=> date("Y-m-d H:i:s")
            ]);
            return redirect()->back()->with(['Success'=>"ลบสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'ลบไม่สำเร็จ']);
        }  
    }

    //-----------------------[ GROUP ]----------------------
    public function group() {
        $data = DB::table("ref_parcel_group")->get();
        return view('backend/setting/group', [
            "data"=>$data
        ]);
    }
    public function group_status($id, $val) {
        $data = DB::table("ref_parcel_group")->where('id', $id)->update([
            'status'=>$val
        ]);
        return response()->json(['msg'=>'Success'] );
    }

    //-----------------------[ USERS ]----------------------
    public function user() {
        $data = DB::table("users")->get();
        return view('backend/setting/user', [
            "data"=>$data
        ]);
    }
    public function user_add(Request $request) {
        try {
            $data = [];
            $data['id'] = $request->cid;
            $data['name'] = $request->name;
            $data['role_parcel'] = empty($request->role_parcel) ? 0 : 1;
            $data['role_report'] = empty($request->role_report) ? 0 : 1;
            $data['role_setting'] = empty($request->role_setting) ? 0 : 1;
            DB::table("users")->insert($data);
            return redirect()->back()->with(['Success'=>"บันทึกสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'บันทึกไม่สำเร็จ']);
        }  
    }
    public function user_update($id, Request $request) {
        try {
            $data = [];
            $data['id'] = $request->cid;
            $data['name'] = $request->name;
            $data['role_parcel'] = empty($request->role_parcel) ? 0 : 1;
            $data['role_report'] = empty($request->role_report) ? 0 : 1;
            $data['role_setting'] = empty($request->role_setting) ? 0 : 1;
            DB::table("users")->where('id', $id)->update($data);
            return redirect()->back()->with(['Success'=>"อัพเดทสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'อัพเดทไม่สำเร็จ']);
        } 
    }
    public function user_delte($id) {
        try {
            DB::table("users")->where('id', $id)->delete();
            return redirect()->back()->with(['Success'=>"ลบสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'ลบไม่สำเร็จ']);
        }  
    }
}

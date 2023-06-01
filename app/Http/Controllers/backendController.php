<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CmsHelper as cms;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Exception;
use Image;
use App\Models\store_order;
use App\Models\store_list;

class backendController extends Controller
{
    public function logout() {
        session()->forget('role');
        return redirect()->route('keycloak.logout');
    }

    public function parcelRegister() {
        $query = DB::table("ref_parcel_group")->select("id","name")->where("status",1)->get();
        $group = cms::ObjArr($query);
        $area = cms::toArray("ref_area");
        $data = DB::table("parcel_detail")->whereNull("deleted_at")->get();
        
        return view('backend/parcel/register', [
            "group"=>$group,
            "area"=>$area,
            "data"=>$data
        ]);
    }
    public function parcel_add(Request $request) {
        $rule = array();
        $rule['image'] = 'image|mimes:png,jpg|max:10240';
        $rule['parcel_id'] = ['required'];
        $rule['name'] = ['required', 'string', 'max:255'];
        $rule['unit'] = ['required', 'string', 'max:255'];
        $rule['area_id'] = ['required'];
        $validator = Validator::make($request->all(), $rule, []);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->all()]);
        }
        //----------------------------------------------------
        $input = [];
        if ($request->file('image') != NULL) {
            $image = $request->file('image');
            $input['pic'] = time().'.'.$image->extension();
     
            $destinationPath = public_path('/assets/parcel');
            $img = Image::make($image->path());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['pic']);
       
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $input['pic']);
        }
        
        //----------------------------------------------------
        try {
            $input['parcel_id'] = $request->parcel_id;
            $input['code'] = $request->code;
            $input['name'] = $request->name;
            $input['detail'] = $request->detail;
            $input['unit'] = $request->unit;
            $input['area_id'] = $request->area_id;
            if( $request->area_red > 0 ) {
                $input['red'] = $request->area_red;
            }
            if( $request->area_green > 0 ) {
                $input['green'] = $request->area_green;
            }
            DB::table("parcel_detail")->insert($input);
            return redirect()->back()->with(['Success'=>"บันทึกสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'บันทึกไม่สำเร็จ']);
        }  
    }
    public function parcel_update($id, Request $request) {
        $rule = array();
        $rule['image'] = 'image|mimes:png,jpg|max:10240';
        $rule['parcel_id'] = ['required'];
        $rule['name'] = ['required', 'string', 'max:255'];
        $rule['unit'] = ['required', 'string', 'max:255'];
        $rule['area_id'] = ['required'];
        $validator = Validator::make($request->all(), $rule, []);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->all()]);
        }
        //----------------------------------------------------
        if ($request->file('image') != NULL) {
            if(!empty($old_pic)) {
                $old_pic = $request->old_pic;
                $target = public_path('/assets/parcel');
                unlink($target."/".$old_pic);
            }
        }
        //----------------------------------------------------
        $input = [];
        if ($request->file('image') != NULL) {
            $image = $request->file('image');
            $input['pic'] = time().'.'.$image->extension();
     
            $destinationPath = public_path('/assets/parcel');
            $img = Image::make($image->path());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['pic']);
       
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $input['pic']);
        }
        //----------------------------------------------------
        try {
            $input['parcel_id'] = $request->parcel_id;
            $input['code'] = $request->code;
            $input['name'] = $request->name;
            $input['detail'] = $request->detail;
            $input['unit'] = $request->unit;
            $input['area_id'] = $request->area_id;
            if( $request->area_red > 0 ) {
                $input['red'] = $request->area_red;
            }
            if( $request->area_green > 0 ) {
                $input['green'] = $request->area_green;
            }
            DB::table("parcel_detail")->where("id",$id)->update($input);
            return redirect()->back()->with(['Success'=>"บันทึกสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'อัพเดทไม่สำเร็จ']);
        } 
    }
    public function parcel_delte($id) {
        try {
            DB::table("parcel_detail")->where('id', $id)->update([
                "deleted_at"=>date("Y-m-d H:i:s")
            ]);
            return redirect()->back()->with(['Success'=>"ลบสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'ลบไม่สำเร็จ']);
        }  
    }

    public function parcel_status($id, $val) {
        $data = DB::table("parcel_detail")->where('id', $id)->update([
            'status'=>$val
        ]);
        return response()->json(['msg'=>'Success'] );
    }

    

    public function parcelIn() {
        $data = DB::table("parcel_detail")->whereNull("deleted_at")->get();
        return view('backend/parcel/in', [
            "data"=>$data
        ]);
    }
    public function parcelInAdd(Request $request) {
        try {
            //--------------[ใบรายการ]-----------
            $store_order = new store_order();
            $store_order->order_number = $request->order_number;
            $store_order->user_create = Session::get('cid');
            $store_order->purchase_date = $request->purchase_date;
            $store_order->save();
            $order_id = $store_order->id;
            //--------------[รายละเอียดของใน - ใบรายการ]---------------
            $data = [];
            $data_for_update = [];
            $data_for_log = [];

            $parcel_detail_id = $request->parcel_detail_id;
            $amount = $request->amount;
            $price = $request->price;

            foreach($parcel_detail_id as $x=>$detail_id) {
                $temp = [   'order_id'=>$order_id,
                            'parcel_detail_id'=>$detail_id, 
                            'balance'=>$amount[$x], 
                            'amount'=>$amount[$x], 
                            'price'=>$price[$x] 
                        ];
                $data[] = $temp;
                //-------------------------------
                $data_for_update[$detail_id] = intval($amount[$x]);
            }
            $store_list = new store_list();
            $store_list->insert($data);
            //------------[ อัพเดท - parcel_detail ]--------------
            $parcel_list = cms::toArray("parcel_detail","id","balance");
            foreach($data_for_update as $id=>$val) {
                $balance = $parcel_list[$id] + $val;
                DB::table('parcel_detail')->where('id', $id)->update([
                    "balance" => $balance,
                    "updated_at" => date("Y-m-d")
                ]);

                $data_for_log[] = [
                    "parcel_detail_id"=>$id,
                    "amount"=>$val,
                    "created_at" => date("Y-m-d"),
                    "user_id" => Session::get('cid')
                ];
            }
            DB::table('parcel_log')->insert($data_for_log);
            //----------------------------------------------------
            return redirect()->back()->with(['Success'=>"บันทึกสำเร็จ"]);
        }catch (Exception $e) {
            return redirect()->back()->with(['Error'=>'บันทึกไม่สำเร็จ']);
        } 
    }


    public function parcelOut() {
        $data = [];
        return view('backend/parcel/out', [
            "data"=>$data
        ]);
    }
}

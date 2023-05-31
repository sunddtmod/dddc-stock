<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CmsHelper as cms;
use Illuminate\Support\Facades\Validator;
use Exception;
use Image;

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
            $old_pic = $request->old_pic;
            $target = public_path('/assets/parcel');
            unlink($target."/".$old_pic);
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
    public function parcelOut() {
        $data = [];
        return view('backend/parcel/out', [
            "data"=>$data
        ]);
    }
}

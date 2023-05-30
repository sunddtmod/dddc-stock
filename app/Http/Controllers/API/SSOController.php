<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vizir\KeycloakWebGuard\Facades\KeycloakWeb;

class SSOController extends Controller
{
    public function ProfileData($idcard) {
        $token = KeycloakWeb::retrieveToken()['access_token'];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://hr-ddc.moph.go.th/api/employee/'.$idcard, [
            'headers'=>[
                'Content-Type'=>'application/json',
                'Authorization'=>'Bearer '.$token
            ],
            'verify'=>false, //ไม้ต้องตรวจ SSL
            'connect_timeout'=>30 //หน่วยเป็นวินาที
        ]);
        $data = json_decode($response->getBody(),true);
        $data = $data['data'];

        $arr = array();
        $title = array('', 'นาย', 'นาง', 'นางสาว');
        $title[5]="ว่าที่ ร.ต.";
        $title[13]="จ่าสิบตรี";
        if (!empty($data)) {
            if( $data['status_id'] == 1 ) {
                $arr['user_id'] = $data['employee_id'];
                $arr['fname'] = trim($data['fname']);
                $arr['user_name'] = (empty($title[$data['title_id']]) ? 'คุณ' : $title[$data['title_id']]) . trim($data['fname']) . " " . trim($data['lname']);;
            }
        }
        return $arr;
    }


    public function avatar($idcard) {
        $token = KeycloakWeb::retrieveToken()['access_token'];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://hr-ddc.moph.go.th/api/employee/'.$idcard, [
            'headers'=>[
                'Content-Type'=>'application/json',
                'Authorization'=>'Bearer '.$token
            ],
            'verify'=>false, //ไม้ต้องตรวจ SSL
            'connect_timeout'=>30 //หน่วยเป็นวินาที
        ]);
        $data = json_decode($response->getBody(),true);
        $arr = array();

        //หน้าตรง (ไม่สวมหน้ากากอนามัย)
        $arr['nomask'] = "https://hr-ddc.moph.go.th/api/file/".$data['data']['fac_picture']['pic_1'];
        //หน้าตรง (สวมหน้ากากอนามัย)
        $arr['mask'] = "https://hr-ddc.moph.go.th/api/file/".$data['data']['fac_picture']['pic_2'];
        //รูปชุดปกติขาว
        $arr['white'] = "https://hr-ddc.moph.go.th/api/file/".$data['data']['fac_picture']['pic_5'];
        //รูปติดบัตร
        $arr['card'] = "https://hr-ddc.moph.go.th/api/file/".$data['data']['fac_picture']['pic_6'];

        return $arr;
    }
}
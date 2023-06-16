<?php

namespace App;
use Illuminate\Support\Facades\DB;

class CmsHelper
{
    public function __construct()
    {
        //echo 'test';
    }

    //=====================================================
    //หมวด วัน เวลา
    //=====================================================
    //ใช้แล้วของมูลวันที่ เช่น 2021-01-11 08:04:01 เป็นวันที่ภาษาไทย
    //ตัวอย่างการใช้งาน DateThai($strDate) จะแสดงเป็น 11 มกราคม 2564
    public static function DateThai($strDate, $type = "d-m-y")
    {
        if ($strDate == '0000-00-00' || $strDate == '' || $strDate == null) {
            return '-';
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strWeek = date("w", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthWeek = array("", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์", "อาทิตย์");
        $strMonthNick = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthFull = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

        //d-M-Y       11 มกราคม 2564
        //d-m-Y       11 ม.ค. 2564
        //w-d-M-Y     ประจำวันจันทร์ที่ 11 มกราคม 2564
        //M-Y         มกราคม 2564
        //d-m-Y H:i   11 ม.ค. 2564 เวลา 14:30
        //H:i:s       14:30:12
        //H.i         14.30 น.

        if ($type == 'd-M-Y') {
            $strMonthThai = $strMonthFull[$strMonth];
            return "$strDay $strMonthThai $strYear";
        } elseif ($type == 'd-m-y') {
            $strMonthThai = $strMonthNick[$strMonth];
            $strYear = substr($strYear,2,2);
            return "$strDay $strMonthThai $strYear";
        } elseif ($type == 'Y-m-d') {
            return $strYear."-". str_pad($strMonth,2,"0",STR_PAD_LEFT) ."-". str_pad($strDay,2,"0",STR_PAD_LEFT);
        } elseif ($type == 'd-m-Y') {
            $strMonthThai = $strMonthNick[$strMonth];
            return "$strDay $strMonthThai $strYear";
        } elseif ($type == 'w-d-M-Y') {
            $strWeekThai = $strMonthWeek[$strWeek];
            $strMonthThai = $strMonthFull[$strMonth];
            return "ประจำวัน" . $strWeekThai . "ที่ " . $strDay . " " . $strMonthThai . " " . $strYear;
        } elseif ($type == 'M-Y') {
            $strMonthThai = $strMonthFull[$strMonth];
            return "$strMonthThai $strYear";
        } elseif ($type == 'd-m-Y H:i') {
            $strMonthThai = $strMonthNick[$strMonth];
            return "$strDay $strMonthThai $strYear เวลา $strHour:$strMinute";
        } elseif ($type == 'H:i:s') {
            return $strHour . ":" . $strMinute . ":" . $strSeconds;
        } elseif ($type == 'H.i') {
            return intval($strHour) . "." . intval($strMinute) . " น.";
        }
    }

    public static function encode($string, $keycode) {
        $string = str_pad($string, 16, '#', STR_PAD_LEFT);
        $key = sha1( $keycode );
        $strLen = strlen($string);
        $keyLen = strlen($key);
        $j=0;	$hash = '';
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($string,$i,1));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
        }
        return $hash;
    }
    
    public static function decode($string, $keycode) {
        $key = sha1( $keycode );
        $strLen = strlen($string);
        $keyLen = strlen($key);
        $j=0; $hash='';
        for ($i = 0; $i < $strLen; $i+=2) {
            $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $hash .= chr($ordStr - $ordKey);
        }
        $hash = str_replace("#","",$hash);
        return $hash;
    }
    public static function decode_name($pname, $fname, $lname) {
        $name = CmsHelper::decode($pname, ENV('ENCODEKEY'));
        $name .= CmsHelper::decode($fname, ENV('ENCODEKEY'));
        $name .= " ";
        $name .= CmsHelper::decode($lname, ENV('ENCODEKEY'));
        return $name;
    }

    public static function toArray($tb, $index='id', $val='name')
    {
        //query table เป็น array
        //$arr = cms::toArray('ref_table');
        $arr = array();
        $query = DB::table($tb);
        $data = $query->get();
        
        foreach ($data as $item) {
            $arr[$item->$index] = $item->$val;
        }
        return $arr;
    }
    public static function toArrayOne($tb, $val='name')
    {
        $arr = array();
        $query = DB::table($tb);
        $data = $query->get();
        
        foreach ($data as $item) {
            $arr[] = $item->$val;
        }
        return $arr;
    }
    public static function ObjArr($obj, $index='id', $val='name')
    {
        $arr = array();
        foreach ($obj as $item) {
            $arr[$item->$index] = $item->$val;
        }
        return $arr;
    }

    public static function quickRandom($length = 5)
    {
        // $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}

?>
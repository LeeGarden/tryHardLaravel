<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function test()
    {
        $test = DB::raw('DATE_SUB(NOW(), INTERVAL 1 DAY)');
        return $test;
    }
    public function getUploadMulti()
    {
        return view('upload');
    }

    public function postUploadMulti(UploadRequest $request)
    {
        return 'ok';
    }
    public function testClient(Request $request)
    {
        $client = new Client();
        $prefecCode = $request->PrefectureCode;
        $detailFlag = $request->DetailFlag;
        $res = [];
        $hotelCode = '';
        if($detailFlag == 0){
            $res = $client->get("https://saas.hotel-story.ne.jp/Pub/api/MIHQ0/001/Facility/Information?PrefectureCode=02,03&DetailFlag=0")->getBody();
            $res = json_decode($res);
            foreach($res as $key => $value){
                $hotelCode = $value->HotelCode1;
                if(substr($hotelCode,0,3) == "MRT"){
                    unset($res[$key]);
                }
            }
            $temp  = "";
            $arrLang = [];
            foreach($res as $key => $value){
                $hotelCode = substr($value->HotelCode1,0,4);
                $lang   = [
                    "HotelCode1"       => $value->HotelCode1,
                    "HotelCode2"       => $value->HotelCode2,
                    "HotelName"        => $value->HotelName,
                    "HotelNameKana"    => $value->HotelNameKana,
                    "HotelNameEnglish" => $value->HotelNameEnglish,
                    "AreaName"         => $value->AreaName,
                    "Prefecture"       => $value->Prefecture,
                    "Address"          => $value->Address
                ];
                unset($value->HotelName,
                      $value->HotelNameKana,
                      $value->HotelNameEnglish,
                      $value->AreaName,
                      $value->Prefecture,
                      $value->Address);
                array_push($arrLang,$lang);
                if($hotelCode == $temp){
                    unset($res[$key]);
                    $res[$position]->list = $arrLang;
                }else{
                    $position = $key;
                    $arrLang = [];
                    array_push($arrLang,$lang);
                    $res[$position]->list = $arrLang;
                }
                $temp = $hotelCode;
            }
            return array_splice($res,0);
        }
    }
    public function removeHotelMRT($respon)
    {
        foreach($respon as $key => $value){
            $hotelCode = $value->HotelCode1;
            if(substr($hotelCode,0,3) == "MRT"){
                unset($respon[$key]);
            }
        }
        return $respon;
    }
    public function sortHotelByLanguage($respon)
    {
        $temp  = "";
        $arrLang = [];
        foreach($respon as $key => $value){
            $hotelCode = substr($value->HotelCode1,0,4);
            $lang   = [
                "HotelCode1"       => $value->HotelCode1,
                "HotelCode2"       => $value->HotelCode2,
                "HotelName"        => $value->HotelName,
                "HotelNameKana"    => $value->HotelNameKana,
                "HotelNameEnglish" => $value->HotelNameEnglish,
                "AreaName"         => $value->AreaName,
                "Prefecture"       => $value->Prefecture,
                "Address"          => $value->Address
            ];
            unset($value->HotelName,
                  $value->HotelNameKana,
                  $value->HotelNameEnglish,
                  $value->AreaName,
                  $value->Prefecture,
                  $value->Address);
            array_push($arrLang,$lang);
            if($hotelCode == $temp){
                unset($respon[$key]);
                $respon[$position]->list = $arrLang;
            }else{
                $position = $key;
                $arrLang = [];
                array_push($arrLang,$lang);
                $respon[$position]->list = $arrLang;
            }
            $temp = $hotelCode;
        }
        return $respon;
    }
    public function updateQuantityHotel()
    {
        $client = new Client();
        $arr = [];
        for ($i=1; $i <=47 ; $i++) {
            if($i < 10)
            {
                $code = '0'.$i;
            }else{
                $code = $i;
            }
            try {
                $res = $client->get('http://saas.hotel-story.ne.jp/Pub/api/MIHQ0/001/Facility/Information?PrefectureCode='.$code.'&DetailFlag=0')->getBody();
                $res = json_decode($res);
                $res = $this->removeHotelMRT($res);
                $res = $this->sortHotelByLanguage($res);
            } catch (ClientException $e) {
                $res = [];
            }
            $arr[$i] = count($res);
        }
        return $arr;
    }
}

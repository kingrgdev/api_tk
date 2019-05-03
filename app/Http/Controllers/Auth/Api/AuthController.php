<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\DateTimeAPI;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use DateTime;

class AuthController extends Controller
{
    public function timekeeping(Request $request){
        $validatedData = $request->validate([
            'email'=>'required', //Added
            'password'=>'required', //Added
            'ACNo'=>'required', //Added
            'name'=>'required',
            'datetime'=>'required', //Added
            'state'=>'required', //Added
            'deviceID'=>'required', //Added
            'status'=>'required' //Added
        ]);

        $user_info = User::where('email',$request->email)->first();


        // $myACNo = $request->ACNo;
        // $myDatetime = date("Y-m-d H:i:s",strtotime($request->datetime));
        // $myState = $request->state;

        // $select_validate_query = "SELECT * FROM date_time_records WHERE ACNo = '".$myACNo."' AND datetime = '".$myDatetime."' AND state = '".$myState."'";
        // $select_validate = DB::connection('mysql')->select($select_validate_query);


        if(!$user_info){
            return response(['status'=>'error','message'=>'User not found']);
        }
        
        if(Hash::check($request->password, $user_info->password)){

            // if(!empty($select_validate)){
            //     return response(['status'=>'error',
            //         'Datetime'=>$myDatetime,
            //         'state'=>$myState,
            //         'message'=>'DateTime and state for this day already exists!'
            //     ]);
            // }else{

                $http = new Client;

                $response = $http->post(url('oauth/token'), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => '2',
                        'client_secret' => 'tZ7E9YiCamCHec07TccW41UbHWB8jaFXi8Py3BgM',
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '',
                    ],
                ]);

                $DTR = new DateTimeAPI;
                $DTR->ACNo = $request->ACNo; //Addded
                $DTR->name = $request->name;
                $DTR->email = $request->email;
                $DTR->apiKey ="NONE"; //Addded
                $DTR->datetime = $request->datetime; //Addded
                $DTR->state = $request->state; //Addded
                $DTR->deviceID = $request->deviceID; //Addded
                $DTR->status = $request->status; //Addded
                $DTR->save();
                return response(['status'=>'ok','message'=>'Upload Success']);
            // }
        }else{
            return response(['status'=>'error','message'=>'Incorrect username/password!']);
        }
    }


    // public function login(Request $request){
    //     $request->validate([
    //         'email'=>'required',
    //         'password'=>'required'
    //     ]);



    //     $user = User::where('email',$request->email)->first();
    //     if(!$user){
    //         return response(['status'=>'error','message'=>'User not found']);
    //     }
    //     if(Hash::check($request->password, $user->password)){
    //         $http = new Client;

    //         $response = $http->post(url('oauth/token'), [
    //             'form_params' => [
    //                 'grant_type' => 'password',
    //                 'client_id' => '2',
    //                 'client_secret' => 'tZ7E9YiCamCHec07TccW41UbHWB8jaFXi8Py3BgM',
    //                 'username' => $request->email,
    //                 'password' => $request->password,
    //                 'scope' => '',
    //             ],
    //         ]);


    //         // return response(['data'=>json_decode((string) $response->getBody(), true)]);
    //         return response(['apiKey'=>$user->apiKey]);
    //     }else{
    //         return response(['status'=>'error','message'=>'Incorrect username/password!']);
    //     }

    // }
}

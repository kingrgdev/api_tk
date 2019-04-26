<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\DateTimeAPI;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function timekeeping(Request $request){
        $validatedData = $request->validate([
            'apiKey'=>'required', //Added
            'ACNo'=>'required', //Added
            'name'=>'required',
            'datetime'=>'required', //Added
            'state'=>'required', //Added
            'deviceID'=>'required', //Added
            'status'=>'required', //Added
            'email'=>'required',
            'password'=>'required'
        ]);

        $user_apiKey = User::where('email',$request->email)->first();
        if($user_apiKey->apiKey != $request->apiKey){
            return response(['status'=>'error','message'=>'Incorrect apiKey!']);
        }else if($user_apiKey->ACNo != $request->ACNo){
            return response(['status'=>'error','message'=>'Incorrect ACNo']);
        }else{
            if(Hash::check($request->password, $user_apiKey->password)){
                $DTR = new DateTimeAPI;
                $DTR->ACNo = $request->ACNo; //Addded
                $DTR->name = $request->name;
                $DTR->email = $request->email;
                $DTR->apiKey = $request->apiKey; //Addded
                $DTR->datetime = $request->datetime; //Addded
                $DTR->state = $request->state; //Addded
                $DTR->deviceID = $request->deviceID; //Addded
                $DTR->status = $request->status; //Addded
                $DTR->save();
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
                // return response(['data'=>json_decode((string) $response->getBody(), true)]);
                return response(['message'=>'Upload Success']);
            }else{
                return response(['status'=>'error','message'=>'Incorrect password!']);
            }
        }
    }
    public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);



        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response(['status'=>'error','message'=>'User not found']);
        }
        if(Hash::check($request->password, $user->password)){
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


            // return response(['data'=>json_decode((string) $response->getBody(), true)]);
            return response(['apiKey'=>$user->apiKey]);
        }else{
            return response(['status'=>'error','message'=>'Incorrect username/password!']);
        }

    }
}

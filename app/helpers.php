<?php

function storage()
{
    return config('app.domain') . 'storage/';
}

function validPhone()
{
    return 'regex:/^09[0-9]{9}$/';
}

function saveImageOnDisk(Illuminate\Http\UploadedFile $image): string
{
    return $image->store('images');
}

function jsonResponse($data, $code = 200)
{
    return response()->json($data, $code);
}

function deleteFromDisk($path)
{
    filled($path) ? Illuminate\Support\Facades\Storage::delete($path) : '';
}

function sendSMS(string $to, string $message)
{
    $url = 'https://ippanel.com/services.jspd';
        
    $param = [
        'uname'=> config('app.sms_uname'),
        'pass'=> config('app.sms_pass'),
        'from'=> '+983000505',
        'message'=> $message,
        'to'=> $to,
        'op'=>'send'
    ];
                
    $handler = curl_init($url);
    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handler);
    
    $response = json_decode($response);

    return $response[0] == 0 ? true : false;
}

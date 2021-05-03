<?php

function storage()
{
    return config('app.domain').'storage/';
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

function sendSMS(string $to, string $pattern, array $data)
{
    $url = 'http://ippanel.com/class/sms/wsdlservice/server.php?wsdl';
    $user = config('app.sms_uname');
    $pass = config('app.sms_pass');
    $from = '+983000505';
    $pattern_code = $pattern;
    $input_data = $data;

    $client = new SoapClient($url);

    $client->sendPatternSms(
        $from,
        $to,
        $user,
        $pass,
        $pattern_code,
        $input_data
    );
}

function makeSlug(string $input): string
{
    $string = preg_replace('/[^a-zA-Zالف-ی0-9_\s-]/', '', $input);
    $string = preg_replace('/[\s-]+/', " ", $string);
    return preg_replace("/[\s_]/", '-', $string);
}

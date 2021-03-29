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
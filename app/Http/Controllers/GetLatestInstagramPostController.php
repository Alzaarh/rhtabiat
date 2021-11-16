<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessCart;

class GetLatestInstagramPostController
{
    public function __invoke()
    {
        $files = Storage::files('images/rhtabiat');
        if (count($files) > 0) {
            return response()->json([
                'data' => [
                    'post_url' => url('storage/' . $files[count($files) - 1]),
                ],
            ]);
        }
        return response()->json(['data' => ['post_url' => null]]);
    }
}

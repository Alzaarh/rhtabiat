<?php

namespace App\Http\Controllers;

use App\Http\Resources\PosterResource;
use App\Models\Poster;
use Illuminate\Http\Request;

class PosterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:admin'])->except('index');
    }

    public function index(Request $request)
    {
        return new PosterResource($request->whenHas('dashboard', function () {
            return Poster::where('location', Poster::LOCATIONS['dashboard'])
                ->where('is_active', true)
                ->first();
        }));
    }

    public function store(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);
        return jsonResponse(Poster::create([
            'image' => saveImageOnDisk($request->image),
        ]), 201);
    }

    public function destroy(Poster $poster)
    {
        $poster->delete();
        return jsonResponse($poster);
    }
}

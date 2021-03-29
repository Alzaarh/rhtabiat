<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poster;

class PosterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'role:admin'])->except('index');
    }

    public function index()
    {
        return jsonResponse(Poster::all());
    }

    public function store(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);
        return jsonResponse(Poster::create([
            'image' => saveImageOnDisk($request->image)
        ]), 201);
    }

    public function destroy(Poster $poster)
    {
        $poster->delete();
        return jsonResponse($poster);
    }
}

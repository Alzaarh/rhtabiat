<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Comment;
use App\Http\Resources\CommentResource;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => Admin::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|string|max:30|min:6',
            'role' => ['required', Rule::in([1, 2, 3, 4])],
        ]);
        $admin = Admin::create($data);
        return response()->json(['data' => $admin], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return response()->json(['data' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                function ($attr, $value, $fail) use ($admin) {
                    $existAdmin = Admin::where('username', $value)->first();
                    if ($existAdmin && $existAdmin->id !== $admin->id) {
                        $fail('username already exist');
                    }
                },
            ],
            'password' => 'required|string|max:30|min:6',
            'role' => ['required', Rule::in([1, 2, 3, 4])],
        ]);
        $admin->update($data);
        return response()->json(['data' => $admin]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(['data' => $admin]);
    }

    public function login(Request $request)
    {
        return jsonResponse(['data' => [
            'token' => Admin::auth($request->validate([
                'username' => 'required|string|max:255',
                'password' => 'required|string|max:30',
            ]))
        ]]);
    }

    // Get all roles
    public function roles()
    {
        // return response()->json(['data' => ['roles' => Admin:]]);
    }

    // Get all comments
    public function getComments(Request $request)
    {
        $request->validate([
            'count' => 'integer|min:1|max:15',
            'status' => 'in:1,2,3',
        ]);
        $query = Comment::query();
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }
        $query->latest();
        return CommentResource::collection($query->paginate($request->query('count')));
    }

    // Get single comment
    public function getComment(Request $request, Comment $comment)
    {
        return new CommentResource($comment);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'OK']);
    }

    public function verifyComment(Request $request, Comment $comment)
    {
        $request->validate(['verify' => 'required|boolean']);
        if ($comment->status !== 1) {
            return response()->json(['message' => 'not found'], 404);
        }
        if ($request->verify) {
            $comment->status = 2;
            $comment->save();
            return response()->json(['data' => new CommentResource($comment)]);
        }
        $comment->status = 3;
        $comment->save();
        return response()->json(['data' => new CommentResource($comment)]);
    }
}

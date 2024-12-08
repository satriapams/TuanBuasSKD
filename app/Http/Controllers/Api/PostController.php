<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('category', 'tags')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Ditemukan',
            'data' => $posts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'status' => 'required|in:published,archived',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|integer',
            'tag_id' => 'nullable|exists:tags,id',
        ]);

        $item = Post::create($validated);
        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Post::find($id);
        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Item found',
            'data' => $item
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Post::find($id);
        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'sometimes|in:published,archived',
            'category_id' => 'sometimes|exists:categories,id',
            'user_id' => 'required|integer',
            'tag_id' => 'nullable|exists:tags,id',
        ]);

        $item->update($validated);
        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $item->load('category', 'tags')
        ], 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Post::find($id);
        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
                'data' => null
            ], 404);
        }

        $item->delete();
        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully',
            'data' => null
        ], 200);
    }
}
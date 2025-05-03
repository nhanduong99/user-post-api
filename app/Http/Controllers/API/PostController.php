<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        $posts = $this->postRepository->all();
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    public function store(PostRequest $request)
    {
        $post = $this->postRepository->create($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => $post
        ], 201);
    }

    public function show($id)
    {
        try {
            $post = $this->postRepository->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $post
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function update(PostRequest $request, $id)
    {
        try {
            $this->postRepository->update($id, $request->validated());
            $post = $this->postRepository->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $post
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->postRepository->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function restore($id)
    {
        try {
            $this->postRepository->restore($id);
            $post = $this->postRepository->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $post
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->postRepository->forceDelete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Post not found'
            ], 404);
        }
    }
}

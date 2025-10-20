<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $blogs = Blog::with('user', 'category')->latest()->paginate(10);
        return BlogResource::collection($blogs);
    }

    public function store(StoreBlogRequest $request)
    {
        $blog = $request->user()->blogs()->create($request->validated());

        if ($request->hasFile('image')) {
            $blog->addMediaFromRequest('image')->toMediaCollection('blogs');
        }

        return response()->json($blog->load('user:id,name', 'category:id,name'), 201);
    }

    public function show(Blog $blog)
    {
        return $blog->load('user:id,name', 'category:id,name');
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $this->authorize('update', $blog);
        $blog->update($request->validated());

        if ($request->hasFile('image')) {
            $blog->clearMediaCollection('blogs');
            $blog->addMediaFromRequest('image')->toMediaCollection('blogs');
        }

        return response()->json($blog->load('user:id,name', 'category:id,name'));
    }

    public function destroy(Blog $blog)
    {
        // $this->authorize('delete', $blog);
        if (! Gate::allows('create', Blog::class)) {
            abort(403);
        }
        $blog->delete();

        return response()->json(['message' => 'Blog post successfully deleted.']);
    }
}

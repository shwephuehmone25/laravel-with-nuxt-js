<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\CategoryPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::filter(request('search'))
            ->latest('created_at')
            ->with('categories')
            ->get();

        return response()->json($posts);
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $posts = POST::all();
        return response()->json($posts);
    }

    /**
     * Store a newly created posts in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $imageName = time() . '.' . $request->image->extension();
        // Storage Folder
        $request->image->move(storage_path('app/public/img/posts'), $imageName);
        $post = Post::create([
            'image' => $imageName,
            'title' => $request->title,
            'description' => $request->description,
        ]);
        $post->categories()->sync($request->categories);
        return response([
            'message' => 'A post created successsfully',
            'data' => $post,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts = Post::find($id);
        $categories = CategoryPost::where('post_id', $id)->pluck('category_id');

        return response()->json([
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
     * Store updated post in database.
     *
     * @param  \Illuminate\Http\PostRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        if ($request->file('image')) {
            if (true) {
                unlink(storage_path('app/public/img/posts/') . $post->image);
            }
            $imageName = time() . '.' . $request->file('image')->extension();

            // Storage Folder
            $request->image->move(storage_path('app/public/img/posts'), $imageName);
            $post->image = $imageName;
        }
        $post->title = $request->title;
        $post->description = $request->description;
        $post->categories()->sync($request->categories);
        $post->save();

        return response([
            'message' => 'Post Updated',
            'data' => $post,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (true) {
            unlink(storage_path('app/public/img/posts/') . $post->image);
        }
        $post->categories()->sync([]);
        $post->delete();

        return response([
            'message' => 'A Post deleted successfully!',
        ]);
    }

    /**
     * Summary of relatedPosts
     * @param mixed $posts_id
     * @return mixed
     */
    public function relatedPosts($id)
    {
        $currentPost = Post::find($id);
        if ($currentPost) {
            $currentCategory = $currentPost->category->id;
            return Post::where("id", "!=", $id)
                ->where("category_id", $currentCategory)
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }
    }
}
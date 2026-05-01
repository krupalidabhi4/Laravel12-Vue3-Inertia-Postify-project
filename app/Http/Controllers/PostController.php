<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Exception;

class PostController extends Controller
{

    public function index()
    {
        try {
            $posts = Post::with('user')->paginate(10);
            return Inertia::render('Posts/Index', ['posts' => $posts]);
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load posts: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Posts/Create');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load create page: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $post = Post::findOrFail($id);
            return Inertia::render('Posts/Edit', ['post' => $post]);
        } catch (Exception $e) {
            return back()->with('error', 'Post not found or failed to load: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|unique:posts,title',
                'body' => 'required',
                'image' => 'required|image|mimes:jpg,png,jpeg'
            ]);

            // Store image
            $imagePath = $request->file('image')->store('posts', 'public');

            // Save to DB
            Post::create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'image' => $imagePath,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('post.index')->with('success', 'Post created successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create post: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $post = Post::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|unique:posts,title,'.$post->id,
                'body' => 'required'
            ]);

            if($request->hasFile('image')) {
                // Store image
                $validated['image'] = $request->file('image')->store('posts', 'public');
            }

            $post->update($validated);

            return redirect()->route('post.index')->with('success', 'Post updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update post: ' . $e->getMessage())->withInput();
        }
    }

    public function listing()
    {
        try {
            $posts = Post::with('user')->latest()->paginate(8);
            return Inertia::render('Posts/Listing', ['posts' => $posts]);
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load listings: ' . $e->getMessage());
        }
    }

    public function show(Post $post)
    {
        try {
            $post->load(['comments.user', 'likes']);

            return Inertia::render('Posts/Show', [
                'post' => $post,
                'liked' => $post->likes()->where('user_id', auth()->id())->exists(),
                'likesCount' => $post->likes()->count(),
                'userId' => auth()->id()
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load post: ' . $e->getMessage());
        }
    }

    public function toggleLike(Post $post)
    {
        try {
            $user = auth()->user();

            $like = $post->likes()->where('user_id', $user->id)->first();

            if ($like) {
                $like->delete(); // unlike
                return back()->with('success', 'Post unliked');
            } else {
                $post->likes()->create(['user_id' => $user->id]);
                return back()->with('success', 'Post liked');
            }
        } catch (Exception $e) {
            return back()->with('error', 'Failed to toggle like: ' . $e->getMessage());
        }
    }

    public function comment(Request $request, Post $post)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:500'
            ]);

            $post->comments()->create([
                'user_id' => auth()->id(),
                'content' => $request->comment
            ]);

            return back()->with('success', 'Comment added successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to add comment: ' . $e->getMessage());
        }
    }

    public function updateComment(Request $request, $id)
    {
        try {
            $comment = Comment::findOrFail($id);

            // Check if user owns this comment
            if ($comment->user_id !== auth()->id()) {
                return back()->with('error', 'Unauthorized: You can only edit your own comments');
            }

            $request->validate([
                'content' => 'required|string|max:500'
            ]);

            $comment->update([
                'content' => $request->content
            ]);

            return back()->with('success', 'Comment updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update comment: ' . $e->getMessage());
        }
    }
}

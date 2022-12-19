<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\SmsContract;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    protected SmsContract $sms;

    public function __construct(SmsContract $sms)
    {
        $this->sms = $sms;
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->with(['category', 'author', 'tags'])->get();
        $tags = Tag::all()->load('posts.author');
        return view('template.home', compact('posts', 'tags'));
    }

    public function create()
    {
        $cats = Category::all();
        $tags = Tag::all();
        $this->sms->sendOtp();
        return view('template.user.posts.create', compact('cats', 'tags'));
    }

    public function store(PostRequest $request)
    {
        $data = $request->all();
        $path = 'post_images/';

        if ($request->file('image')) {
            $path = 'post_images/';
            $image = $request->file('image')->getClientOriginalName() . uniqid(50) . '.' . $request->file('image')->extension();
            $file = $path . $image;
            $request->file('image')->move('post_images',  $file);
            // Storage::disk('public')->put('posts', (string) $request->file('image'));
        } else {
            $file = null;
        }

        $post = Post::insertGetId([
            'title' => $data['title'],
            'author_id' => auth()->user()->id,
            'cat_id' => $data['cat'],
            'content' => $data['content'],
            'image' => $file,
            'slug' => SlugService::createSlug(Post::class, 'slug', $data['title']),
            'created_at' => Carbon::now(),
        ]);

        foreach ($data['tags'] as $tag) {
            DB::table('posts_tags')->insert(['post_id' => $post, 'tag_id' => $tag]);
        }

        toast('New post added!', 'success');
        return redirect()->route('template.index');
    }

    public function edit($slug)
    {
        $post = Post::findBySlugOrFail($slug)->load(['category', 'tags', 'author']);
        $cats = Category::all();
        $tags = Tag::all();
        return view('template.user.posts.edit', compact('post', 'cats', 'tags'));
    }

    public function update(PostUpdateRequest $request, $slug)
    {
        $data = $request->validated();
        $post = Post::findBySlugOrFail($slug);
        $old = $post->image;

        if ($request->file('image')) {
            if ($old) {
                // Storage::disk('upload')->put();
                unlink('post_images/' . $old);
                $image = uniqid(50) . '.' . $request->file('image')->extension();
                $request->file('image')->move('post_images',  $image);
            } else {
                $image = uniqid(50) . '.' . $request->file('image')->extension();
                $request->file('image')->move('post_images',  $image);
            }
        } else {
            if ($old) {
                $image = $old;
            } else {
                $image = null;
            }
        }

        $post->update([
            'title' => $data['title'],
            'cat_id' => $data['cat'],
            'content' => $data['content'],
            'author_id' => auth()->user()->id,
            'image' => $image,
        ]);

        $data['image'] = $image;

        $post->update([
            ...$data,
            'author_id' => auth()->id(),
        ]);

        $post->tags()->sync($data['tags']);

        // DB::table('posts_tags')->where('post_id', $post->id)->delete();

        // foreach ($data['tags'] as $tag) {
        //     DB::table('posts_tags')->insert(['post_id' => $post->id, 'tag_id' => $tag]);
        // }

        toast('Post was updated!', 'success');
        return redirect()->route('template.index');
    }

    public function destroy($slug)
    {
        $post = Post::findBySlugOrFail($slug);

        if ($post->image) {
            if (File::exists($post->image)) {
                unlink($post->image);
            }
        }

        // DB::table('post_tags')->where('post_id', $post->id)->delete();

        $post->delete();
        toast('Post was deleted!', 'info');
        return redirect()->route('template.index');
    }
}

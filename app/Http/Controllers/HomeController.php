<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->with(['category', 'author', 'tags'])->get();
        $tags = Tag::all()->load('posts');
        return view('template.home', compact('posts', 'tags'));
    }
}

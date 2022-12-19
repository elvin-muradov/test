<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'search' => ['required'],
        ], ['required' => 'The :attribute field is required']);

        $search = $request->search;
        $users = User::whereRelation('posts', function ($query) use ($search) {
            $query->whereRelation('tags', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        })->with('posts.tags')->get();

        if ($users->count() > 0) {

            $tags = [];

            foreach ($users as $user) {
                foreach ($user->posts as $post) {
                    array_push($tags, ...$post->tags);
                }
            }

            $tags = collect($tags)->unique('id');

            // $tags = Tag::where('name', 'LIKE', '%' . $search . '%')->get();
            return view('template.search', compact('users', 'search', 'tags'));
        } else {
            return redirect()->route('template.index')->with('err', 'No results!');
        }
    }
}

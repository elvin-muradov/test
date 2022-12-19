@extends('index')

@section('content')
    <form action="{{ route('search.filter') }}" method="GET" class="w-full my-4 mx-auto">
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="search" id="default-search" name="search"
                class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                value="{{ $search }}" placeholder="Search..." />
            @error('search')
                <div class="p-3 mt-2 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                    role="alert">
                    <svg class="inline-block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>
    </form>
    <section class="flex items-start justify-center my-4">
        <div class="mx-auto lg:px-6">
            <h5 class="text-white bg-indigo-900 p-4 rounded-lg my-4 text-center">Tag:
                @foreach ($tags as $tag)
                    <span>#{{ $tag->name }}</span>
                @endforeach
            </h5>
            <h4 class="text-white bg-indigo-900 p-4 rounded-lg my-4 text-center"><span
                    class="text-2xl font-bold">{{ $users->count() }}</span> users
            </h4>
            <ul class="bg-indigo-700 p-4 rounded-lg inline-block">
                @foreach ($users as $key => $user)
                    <li class="text-white w-full">
                        {{ $key + 1 . ') ' }}{{ $user->name . '-' . $user->email }}
                        <ul class="bg-indigo-900 p-4 rounded-lg block">
                            {{ $user->name }}'s posts
                            @foreach ($user->posts as $key => $post)
                                <li class="text-white my-1">{{ $key + 1 . ')' . $post->title }}
                                    @foreach ($post->tags as $tag)
                                        <small
                                            class="bg-red-800 text-white rounded p-0.5 my-1 mx-1">{{ '#' . $tag->name }}</small>
                                    @endforeach
                                </li>
                                <hr>
                            @endforeach
                        </ul>
                    </li>
                @endforeach

            </ul>
            @if (session('message'))
                <h2 class="text-white w-full"><span>{{ session()->get('message') }}</span></h2>
            @endif
            {{-- <div class="my-4">
                {{ $posts->links('vendor.pagination.tailwind') }}
            </div> --}}
        </div>
    </section>
@endsection

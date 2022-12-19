@extends('index')

@section('content')
    <section class="flex flex-col items-start justify-center my-4 p-4">
        @if (session()->has('message'))
            <div class="p-3 mt-2 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert">
                <svg class="inline-block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session()->get('message') }}
            </div>
        @endif

        {{-- <ul class="p-6 flex flex-wrap mx-auto my-4 rounded-lg bg-red-700 text-white">
            @foreach ($tags as $tag)
                <li class="mx-3">#{{ $tag->name }}
                    <ul>
                        @foreach ($tag->posts as $post)
                            <li class="px-4 py-2 my-1 rounded-lg bg-red-900">{{ $post->author->name }}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul> --}}
        <form action="{{ route('search.filter') }}" method="GET" class="w-full my-4 mx-auto">
            <label for="default-search"
                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="search" id="default-search"
                    class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search..." name="search">
            </div>
            @if (session()->has('err'))
                <div class="p-3 mt-2 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                    role="alert">
                    <svg class="inline-block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session()->get('err') }}
                </div>
            @endif
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
        </form>
        <div class="mx-auto max-w-screen-xl lg:px-6">
            <div class="grid gap-4 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <article class="p-3 bg-transparent rounded-lg border border-gray-800 shadow-md">
                        <div class="flex justify-between items-center mb-3 text-gray-500 relative">
                            <img src="{{ $post->image ? url('post_images/' . $post->image) : asset('assets/template/images/blog_img.jpg') }}"
                                alt="Blog">
                            <span
                                class="absolute top-2 left-2 bg-blue-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                </svg>
                                {{ $post->category->name }}
                            </span>
                            <a href="{{ route('posts.edit', $post->slug) }}"
                                class="absolute top-2 right-14 bg-blue-600 text-gray-200 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded"><svg
                                    class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg></a>
                            <form action="{{ route('posts.destroy', $post->slug) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="absolute top-2 right-2 bg-red-600 text-gray-200 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded"><svg
                                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg></button>
                            </form>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight text-white"><a
                                href="#">{{ mb_strimwidth($post->title, 0, 25, '...') }}</a></h3>
                        @foreach ($post->tags as $tag)
                            <span
                                class="bg-red-500 text-gray-100 text-xs font-medium inline-flex items-center my-1 px-1 py-0.5 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                                {{ $tag->name }}
                            </span>
                        @endforeach
                        <div class="flex justify-between items-center my-3">
                            <div class="flex items-center space-x-4">
                                <img class="w-7 h-7 rounded-full"
                                    src="https://www.doesport.co.uk/wp-content/uploads/2017/11/profile-icon-male-avatar-portrait-casual-person-silhouette-face-flat-design-vector-illustration-58249394.jpg"
                                    alt="Jese Leos avatar" />
                                <span class="font-medium text-gray-400">
                                    {{ $post->author->name }}
                                </span>
                            </div>
                            <a href="#" class="inline-flex items-center font-medium  text-gray-400 hover:underline">
                                {{ Carbon\Carbon::parse($post->created_at)->format('d.m.Y') }}
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            {{-- <div class="my-4">
                {{ $posts->links('vendor.pagination.tailwind') }}
            </div> --}}
        </div>
    </section>
@endsection

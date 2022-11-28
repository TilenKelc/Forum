<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Currently viewing {{ $user->username }}'s profile
        </h2>
    </x-slot>
    
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                @if(in_array($user->id, Auth::user()->getAllFriendsID()))
                    <form method="POST" action='{{ url("/user/$user->id/unfollow") }}'>
                @else
                    <form method="POST" action='{{ url("/user/$user->id/follow") }}'>
                @endif
                        @csrf

                        <div class="mb-5">
                            <img class="rounded-full mx-auto" width="100px" height="100px" src="{{ URL::to('/') }}/images/social-media.jpg" alt="media-photo">
                        </div>
                    
                        <!-- Name -->
                        <div class="text-center mb-5">
                            <p class="block mt-1 w-full">{{ $user->username }}</p>
                        </div>

                        <div class="text-center">
                            <div class="inline-block mr-5">
                                <x-label for="username" :value="__('Followers')"/>
                                <p class="block mt-1 w-full">10</p>
                            </div>
                            <div class="inline-block ml-5">
                                <x-label for="username" :value="__('Following')"/>
                                <p class="block mt-1 w-full">12</p>
                            </div>
                        </div>
                    
                        <div class="mt-5 text-center">
                            @if(in_array($user->id, Auth::user()->getAllFriendsID()))
                                <x-button>
                                    Stop following
                                </x-button>
                            @else
                                <x-button>
                                    Start following
                                </x-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(count($posts) != 0)
        @foreach($posts as $post)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- hover:bg-blue-600 hover:scale-200 transition duration-150 ease-in-out -->
                    <div class="flex pt-6 px-6">
                        <div class="w-1/2 underline text-sm italic text-gray-800 leading-tight">
                            <?php 
                                $id = $post->getUser()->id; 
                            ?>
                            <a href='{{ url("/user/$id") }}'>
                                {{ $post->getUser()->username }}
                            </a>
                        </div>
                        <div class="w-1/2 text-right">
                            {{ date('d.m.Y h:i', strtotime($post->created_at)) }}
                        </div>
                    </div>
                    
                    <div class="px-6 pb-3 w-full border-b border-grey-200">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mt-2 change-data">{{ $post->title }}</h3>
                    </div>
                    <div class="px-6 py-6 w-full">
                        @if(strlen($post->content) > 100)
                            {{ substr($post->content, 0, 300) }}... 
                        @else
                            {{ $post->content }}
                        @endif
                        <a href='{{ url("/post/show/$post->id") }}' class="underline text-sm italic">(Read more)</a>
                    </div>

                    <div class="px-6 pb-6 flex">
                        <div class="w-1/2 flex">
                            @if(Auth::check())
                                @if(Auth::user()->isBlocked())
                                    <svg class="w-6 h-6 dark:text-white md:text-xl cursor-pointer show-error-mssg" fill="<?php if($post->isUpvoted()){ echo 'grey'; } else { echo 'none'; } ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                                    <span class="px-2">{{ $post->getlikes(); }}</span>
                                    <svg class="w-6 h-6 dark:text-white md:text-xl cursor-pointer show-error-mssg" fill="<?php if($post->isDownvoted()){ echo 'grey'; } else { echo 'none'; } ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                                @else
                                    <svg class="w-6 h-6 dark:text-white md:text-xl upvote cursor-pointer" id="{{ $post->id }}" fill="<?php if($post->isUpvoted()){ echo 'grey'; } else { echo 'none'; } ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                                    <span class="px-2">{{ $post->getlikes(); }}</span>
                                    <svg class="w-6 h-6 dark:text-white md:text-xl downvote cursor-pointer" id="{{ $post->id }}" fill="<?php if($post->isDownvoted()){ echo 'grey'; } else { echo 'none'; } ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                                @endif
                            @else
                                <svg class="w-6 h-6 dark:text-white md:text-xl redirectBtn cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                                <span class="px-2" id="post-likes">{{ $post->getlikes(); }}</span>
                                <svg class="w-6 h-6 dark:text-white md:text-xl redirectBtn cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>    
                            @endif
                        </div>
                        <div class="w-1/2 text-right">
                            <a href='{{ url("/post/show/$post->id") }}' class="pr-2 cursor-pointer">
                                {{ count($post->getComments()) }} Comments
                            </a>
                            @if(Auth::check() && Auth::id() == $post->user_id)
                                @if(Auth::user()->isBlocked())
                                    <a href='javascript:void(0)' class="pr-2 cursor-pointer show-error-mssg">Edit</a>
                                    <a href='javascript:void(0)' class="cursor-pointer show-error-mssg">Delete</a>  
                                @else
                                    <a href='{{ url("/post/edit/$post->id") }}' class="pr-2 cursor-pointer">Edit</a>
                                    <a href='javascript:void(0)' class="cursor-pointer deleteBtn" id="{{ $post->id }}">Delete</a>
                                @endif    
                            @else
                                @if(Auth::check() && Auth::user()->isAdmin())
                                    <a href='javascript:void(0)' class="cursor-pointer deleteBtn" id="{{ $post->id }}">Delete</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex py-6 px-6">
                    <div class="font-semibold text-md text-gray-800 leading-tight mt-2 change-data">
                        There is no posts
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
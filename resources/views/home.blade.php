<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home page') }}
        </h2>
    </x-slot>

    <!-- Errors and mssgs -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />
    
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
                    <!--<div class="p-6 bg-white border-b border-gray-200">
                        asd
                    </div>-->
                    
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
                                @if(Auth::check() && Auth::user()->isAdmin() == false && Auth::id() != $post->user_id)
                                    <a href="javascript:void(0)" class="reportBtn ml-4 cursor-pointer" id="{{ $post->id }}">
                                        Report
                                    </a>
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

    <script>
        $(document).ready(() => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.show-error-mssg').on('click', function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This action is not allowed, as your account is blocked!',
                });
            });

            $('.deleteBtn').on('click', function(){
                Swal.fire({
                    title: 'Are you sure, you want to delete this post?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let base_link = '{{ URL::to("/") }}';
                        let link = '/post/delete/' + this.id;

                        window.location = base_link + link;
                    }
                })
            });

            $('.upvote').on('click', function(){
                let btn = this;
                $.ajax({
                    method: "POST",
                    url: '{{ route("post.like") }}',
                    data: { id: this.id, like: 1}
                })
                .done(function(response){
                    if(response){
                        btn.parentElement.childNodes[3].innerText = response;
                        let downBtn = btn.parentElement.childNodes[5];
                        
                        if(btn.getAttribute('fill') == 'none'){
                            if(downBtn.getAttribute('fill') != 'none'){
                                downBtn.setAttribute('fill', 'none');
                            }
                            btn.setAttribute('fill', 'grey');
                        }else{
                            btn.setAttribute('fill', 'none');
                        }
                    }
                });
            });

            $('.downvote').on('click', function(){
                let btn = this;
                $.ajax({
                    method: "POST",
                    url: '{{ route("post.dislike") }}',
                    data: { id: this.id, like: 0}
                })
                .done(function(response){
                    if(response){
                        btn.parentElement.childNodes[3].innerText = response;
                        let upBtn = btn.parentElement.childNodes[1];
                        
                        if(btn.getAttribute('fill') == 'none'){
                            if(upBtn.getAttribute('fill') != 'none'){
                                upBtn.setAttribute('fill', 'none');
                            }
                            btn.setAttribute('fill', 'grey');
                        }else{
                            btn.setAttribute('fill', 'none');
                        }
                    }
                });
            });

            $('.reportBtn').on('click', function(){
                Swal.fire({
                    title: 'Are you sure, you want to report this post?',
                    text: "Reported post will be review by admins",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, report it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "POST",
                            url: '{{ route("post.report") }}',
                            data: { id: this.id }
                        })
                        .done(function(response){
                            response = response.replaceAll("\"", "");
                            if(response){
                                $('#errorMssgText').text(response);
                                $('#errorMssg').show();
                            }
                        });
                    }
                })
            });

            $('.redirectBtn').on('click', function(){
                window.location = "{{ url('/login') }}";
            });
        });
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" id="header-txt">
            View post
        </h2>
    </x-slot>

    <x-auth-validation-errors :errors="$errors" />

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Errors and mssgs -->

                    <form method="POST" action='{{ url("/post/save/$post->id") }}'>
                        @csrf
                        
                        <div class="flex">
                            <div class="w-1/2 underline text-sm italic text-gray-800 leading-tight">
                                {{ $post->getUser()->username }}
                            </div>
                            <div class="w-1/2 text-right">
                                {{ $post->created_at }}
                            </div>
                        </div>

                        <!-- Title -->
                        <div>
                            <x-label for="title" value="Title" class="hidden change-form mt-2" />
                            <input type="text" id="title" name="title" class="hidden change-form flex block w-full border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $post->title }}">
                            <h3 class="font-semibold text-lg text-gray-800 leading-tight mt-2 change-data">{{ $post->title }}</h3>
                        </div>
                        
                        <!-- Content -->
                        <div class="mt-2">
                            <x-label for="content" value="Content" class="hidden change-form"/>
                            <textarea
                                class="
                                    form-control
                                    block
                                    w-full
                                    px-3
                                    text-base
                                    font-normal
                                    text-gray-700
                                    bg-white bg-clip-padding
                                    border border-solid border-gray-300
                                    rounded
                                    transition
                                    ease-in-out
                                    m-0
                                    shadow-sm
                                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
                                    hidden
                                    change-form
                                "
                                name="content"
                                rows="10">{{ $post->content }}</textarea>
                                <div class="change-data">{{ $post->content }}</div>
                        </div>
                        
                        <div class="mt-4 flex">         
                            <div class="w-1/2 hidden change-form text-right mr-3">           
                                <x-button>
                                    Update post
                                </x-button>
                            </div>
                            <div class="w-1/2 hidden change-form text-left ml-3">
                                <x-button type="button" id="cancelBtn">
                                    Cancel
                                </x-button>
                            </div>
                        </div>

                        <div class="flex mt-4">
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
                                @if(Auth::check() && Auth::id() == $post->user_id)
                                    @if(Auth::user()->isBlocked())
                                        <a href='javascript:void(0)' class="pr-2 change-data show-error-mssg">Edit</a>
                                        <a href='javascript:void(0)' class="show-error-mssg">Delete</a>
                                    @else
                                        @if(Auth::user()->isAdmin() == false || Auth::id() == $post->user_id)
                                            <a href='javascript:void(0)' class="pr-2 change-data" id="editBtn">Edit</a>
                                        @endif
                                        <a href='javascript:void(0)' id="deleteBtn">Delete</a>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(Auth::check())
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form method="POST" action="{{ url('/comment/save') }}">
                            @csrf
                            
                            <div>
                                <x-label class="text-lg" for="comment" value="Your comment:" />
                                <textarea
                                    class="
                                        form-control
                                        block
                                        w-full
                                        px-3
                                        py-1.5
                                        text-base
                                        font-normal
                                        text-gray-700
                                        bg-white bg-clip-padding
                                        border border-solid border-gray-300
                                        rounded
                                        transition
                                        ease-in-out
                                        m-0
                                        shadow-sm
                                        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none
                                    "
                                    name="content"
                                    rows="5"
                                    required
                                    maxlength="255"
                                ></textarea>
                                <x-input type="hidden" name="post_id" :value="$post->id" required/>
                            </div>
                            
                            <div class="flex items-center justify-end mt-4">
                                @if(Auth::user()->isBlocked())
                                    <x-button class="ml-4 show-error-mssg" type="button">
                                        Comment
                                    </x-button>
                                @else
                                    <x-button class="ml-4">
                                        Comment
                                    </x-button>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif
                <div>
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex">
                            <div class="w-1/2 font-bold text-base italic text-gray-800 leading-tight">
                                @if(count($post->getComments()) > 0)
                                    Comments
                                @else
                                    No comments yet
                                @endif
                            </div>
                        </div>
                    </div>
                    @foreach($post->getComments() as $comment)
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex">
                                <div class="w-1/2 underline text-base italic text-gray-800 leading-tight">
                                    {{ $comment->getUser()->username }}
                                </div>
                                <div class="w-1/2 text-right">
                                    {{ $comment->created_at }}
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="text-base text-gray-800 leading-tight">
                                    {{ $comment->content }}
                                </div>
                            </div>

                            <div class="text-right w-100">

                                @if(Auth::check() && Auth::id() == $comment->user_id)
                                    @if(Auth::user()->isBlocked())
                                        <div class="text-base text-gray-800 text-right">
                                            <a href="javascript:void(0)" class="show-error-mssg">Delete</a>
                                        </div>
                                    @else
                                        <div class="text-base text-gray-800 text-right">
                                            <a href="{{ url('/comment/delete/' . $comment->id) }}">Delete</a>
                                        </div>
                                    @endif    
                                @else
                                    @if(Auth::check() && Auth::user()->isAdmin())
                                        <a href='javascript:void(0)' class="cursor-pointer deleteBtn" id="{{ $post->id }}">Delete</a>
                                    @endif
                                @endif                                
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        if('<?php echo isset($check); ?>' == true){
            $('.change-data').hide();
            $('.change-form').show();
        }
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

            $('#editBtn').on('click', function(){
                $('.change-data').hide();
                $('.change-form').show();
            });

            $('#cancelBtn').on('click', function(){
                $('.change-form').hide();
                $('.change-data').show();
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

            $('#deleteBtn').on('click', function(){
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

            $('.reportBtn').on('click', function(){
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
            });

            $('.redirectBtn').on('click', function(){
                window.location = "{{ url('/login') }}";
            });
        });
    </script>
</x-app-layout>
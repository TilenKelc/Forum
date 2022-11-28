<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add a post
        </h2>
    </x-slot>

    <!-- Errors and mssgs -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    @if(Auth::user()->isBlocked())
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex py-6 px-6">
                    <div class="font-semibold text-md text-gray-800 leading-tight mt-2 change-data">
                        You cannot add any new posts, because your account is blocked
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form method="POST" action="{{ url('/post/save') }}">
                            @csrf
                            
                            <!-- Title -->
                            <div>
                                <x-label for="title" value="Title"/>
                                <x-input id="title" class="flex block mt-1 w-full" type="text" name="title" required maxlength="255"/>
                            </div>
                            
                            <!-- Content -->
                            <div class="mt-4">
                                <x-label for="content" value="Content" />
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
                                    rows="10"
                                    required
                                    maxlength="3000"
                                ></textarea>
                            </div>
                            
                            <div class="flex items-center justify-end mt-4">                    
                                <x-button class="ml-4">
                                    Add new post
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
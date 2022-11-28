<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit profile
        </h2>
    </x-slot>
    
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ url('/user/save') }}">
                        @csrf
                    
                        <!-- Name -->
                        <div>
                            <x-label for="username" :value="__('Username')"/>
                            <x-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ $user->username }}" required />
                        </div>
                    
                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-label for="email" :value="__('Email')" />
                            <x-input id="email" class="block mt-1 w-full bg-gray-100 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500  cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" type="email" name="email" value="{{ $user->email }}" readonly required />
                        </div>
                    
                        <!-- Password -->
                        <div class="mt-4">
                            <x-label for="password" :value="__('Password')" />
                    
                            <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                autocomplete="new-password" />
                        </div>
                    
                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-label for="password_confirmation" :value="__('Confirm Password')"/>
                    
                            <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation"/>
                        </div>
                    
                        <div class="flex items-center justify-end mt-4">
                            @if(Auth::user()->isBlocked())                    
                                <x-button class="ml-4" type="button" class="show-error-mssg">
                                    Update
                                </x-button>
                            @else
                                <x-button class="ml-4">
                                    Update
                                </x-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(() => {
            $('.show-error-mssg').on('click', function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This action is not allowed, as your account is blocked!',
                });
            });
        });
    </script>
</x-app-layout>
<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="flex flex-col space-y-4">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
            
            <div class="flex flex-col sm:flex-row sm:justify-between space-y-2 sm:space-y-0">
                @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-500 underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif

                @if (Route::has('register'))
                <a class="text-sm text-indigo-600 hover:text-indigo-500 underline" href="{{ route('register') }}">
                    {{ __('Make new account') }}
                </a>
                @endif
            </div>
        </div>
    </form>
</x-guest-layout>

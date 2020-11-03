<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="Электронная почта" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Пароль" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Запомнить меня') }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4 w-full">
                <x-jet-button class="justify-center w-full btn-primary">
                    {{ __('Войти') }}
                </x-jet-button>
            </div>
            <div class="flex items-center justify-end mt-4 w-full">
                <a class="inline-flex items-center px-4 py-2 text-center border border-transparent rounded-full font-semibold text-xs tracking-widest disabled:opacity-25 transition ease-in-out duration-150 justify-center w-full btn-secondary" href="{{ route('register') }}">
                    {{ __('Регистрация') }}
                </a>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

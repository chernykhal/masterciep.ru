<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <x-jet-validation-errors class="mb-4"/>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mt-4">
                <x-jet-input id="email" class="block mt-1 w-full" placeholder="Электронная почта" type="email" name="email" :value="old('email')"
                             required/>
            </div>

            <div class="mt-4">
                <x-jet-input id="password" class="block mt-1 w-full" placeholder="Пароль" type="password" name="password" required
                             autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" placeholder="Подтвердите пароль" type="password"
                             name="password_confirmation" required autocomplete="new-password"/>
            </div>
            <div class="block mt-4">
                <label for="confirmation" class="flex items-center">
                    <input id="confirmation" type="checkbox" class="form-checkbox" name="confirmation">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Я согласен с обработкой персональных данных') }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4 w-full">
                <x-jet-button class="justify-center w-full btn-primary">
                    {{ __('Зарегистрироваться') }}
                </x-jet-button>
            </div>
            <div class="flex items-center justify-end mt-4 w-full">
                <a class="inline-flex items-center px-4 py-2 text-center border border-transparent rounded-full font-semibold text-xs tracking-widest disabled:opacity-25 transition ease-in-out duration-150 justify-center w-full btn-secondary"
                   href="{{ route('login') }}">
                    {{ __('Вход') }}
                </a>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>

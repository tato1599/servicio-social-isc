<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col items-center">
                <img src="{{ asset('Liebre Solida.png') }}" alt="ITCJ Logo" class="w-24 mx-auto mb-2 drop-shadow-lg">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">ITCJ</h1>
                <p class="text-gray-500 text-sm mt-1">Sistema de Gestión</p>
            </div>
        </x-slot>

        <x-validation-errors class="mb-6" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Correo Electrónico') }}" class="text-sm font-semibold text-gray-700" />
                <x-input id="email" class="block mt-1 w-full border-gray-200 focus:border-primary focus:ring-primary rounded-xl" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@tecnm.mx" />
            </div>

            <div>
                <x-label for="password" value="{{ __('Contraseña') }}" class="text-sm font-semibold text-gray-700" />
                <x-input id="password" class="block mt-1 w-full border-gray-200 focus:border-primary focus:ring-primary rounded-xl" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <x-checkbox id="remember_me" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary" />
                    <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Recordarme') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-primary hover:text-primary/80 font-medium transition-colors" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>

            <div class="pt-2">
                <x-button class="w-full justify-center bg-primary hover:bg-primary/90 active:bg-primary focus:ring-primary rounded-xl py-3 text-base shadow-lg shadow-primary/20 transition-all">
                    {{ __('Iniciar Sesión') }}
                </x-button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100">
            <p class="text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} TecNM - Campus Ciudad Juárez.<br>
                Subdirección Académica
            </p>
        </div>
    </x-authentication-card>
</x-guest-layout>

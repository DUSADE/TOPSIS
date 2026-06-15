<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center p-6 md:p-0 bg-gradient-to-br from-slate-50 to-primary/10">
        <!-- Logo -->
        <div class="mb-8">
             <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center font-bold text-accent-500 text-2xl shadow-lg shadow-primary/20">
                    C
                </div>
                <span class="text-3xl font-black tracking-tighter text-primary uppercase">CRM<span class="text-accent-600">Property</span></span>
            </div>
        </div>

        <div class="w-full sm:max-w-md bg-white shadow-2xl shadow-primary/10 border border-slate-100 overflow-hidden rounded-3xl" x-data="{ showPassword: false }">
            <div class="p-8 md:p-10">
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h2>
                    <p class="text-sm text-slate-500 mt-2">Silahkan masuk untuk mengelola data prospek Anda.</p>
                </div>

                <!-- Session Status -->
                @if(session('status'))
                    <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-100 font-medium text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-slate-700 tracking-wide uppercase">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full px-4 py-4 text-base rounded-2xl border-slate-300 bg-white shadow-sm focus:border-primary focus:ring-primary/20 transition-all placeholder:text-slate-400 border-2" placeholder="nama@email.com">
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="block text-sm font-bold text-slate-700 tracking-wide uppercase">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary-600 hover:text-primary-800 transition-colors">Lupa Password?</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                class="block w-full px-4 py-4 text-base rounded-2xl border-slate-300 bg-white shadow-sm focus:border-primary focus:ring-primary/20 transition-all placeholder:text-slate-400 border-2" placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-primary-600 transition-colors">
                                <template x-if="!showPassword">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </template>
                                <template x-if="showPassword">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-slate-300 text-primary shadow-sm focus:ring-primary transition-all" name="remember">
                            <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-4 text-base font-black rounded-2xl transition-all shadow-lg shadow-primary/20 bg-primary text-white hover:bg-primary/90 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-primary/30">
                            Masuk Ke Dashboard
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-6 border-t border-slate-50 text-center">
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-widest">
                         &copy; {{ date('Y') }} CRM Property App
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS for Visibility Toggle -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

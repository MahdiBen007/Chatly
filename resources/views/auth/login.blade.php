<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen p-4 text-white">

        <div class="fixed top-4 right-4 z-50">
            <button id="theme-toggle" class="p-2 rounded-full bg-white/10 border border-white/20 hover:bg-white/20 transition">
                <svg id="theme-toggle-dark-icon" xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5 stroke-current text-white"
                    fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                </svg>
                <svg id="theme-toggle-light-icon" xmlns="http://www.w3.org/2000/svg" class="hidden w-5 h-5 stroke-current text-white"
                    fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
                </svg>
            </button>
        </div>

        <!-- Login Card -->
        <div class="backdrop-blur-xl 
            bg-[var(--surface)] 
            border border-[var(--border)] rounded-3xl p-6 sm:p-8 
            shadow-[0_0_10px_rgba(0,223,216,0.15)] w-full max-w-md 
            transition-all duration-300 hover:shadow-[0_0_25px_rgba(0,223,216,0.25)] hover:-translate-y-0.5 opacity-0 animate-fade-in-up">

            <!-- Title -->
            <div class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold mb-2 
                           bg-gradient-to-r from-[#007cf0] to-[#00dfd8] bg-clip-text text-transparent whitespace-nowrap">
                    Welcome Back 👋
                </h1>
                <p class="text-[var(--muted)] text-base tracking-wide font-light">
                    Log in to continue
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div class="space-y-2" x-data="{ showCopy: false }" @mouseenter="showCopy = true" @mouseleave="showCopy = false">
                    <label for="email" class="text-sm font-medium text-[var(--text)]">Email</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <rect width="20" height="16" x="2" y="4" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                        <input id="email" type="email" name="email" placeholder="you@example.com"
                            value="{{ old('email') }}" required autofocus class="w-full pl-11 pr-10 py-2 bg-[var(--surface)] border border-[var(--border)] 
                                   rounded-xl placeholder-[var(--muted)] text-sm text-[var(--text)] 
                                   focus:outline-none focus:ring-2 focus:ring-[#00dfd8] 
                                   focus:shadow-[0_0_20px_rgba(0,223,216,0.25)] transition-all">
                        <button type="button" x-show="showCopy"
                            @click="navigator.clipboard.writeText(document.getElementById('email').value)"
                            class="absolute right-3 top-1/2 -translate-y-1/2" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2" x-data="{ showPassword: false, password: '' }">
                    <label for="password" class="text-sm font-medium text-[var(--text)]">Password</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>

                        <input id="password" x-model="password" :type="showPassword ? 'text' : 'password'"
                            name="password" placeholder="••••••••" required class="w-full pl-11 pr-10 py-2 bg-[var(--surface)] border border-[var(--border)] 
                   rounded-xl placeholder-[var(--muted)] text-sm text-[var(--text)] 
                   focus:outline-none focus:ring-2 focus:ring-[#00dfd8] 
                   focus:shadow-[0_0_20px_rgba(0,223,216,0.25)] transition-all">

                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2"
                            x-show="password.length > 0" @click="showPassword = !showPassword">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .946-3.11 3.56-5.446 6.835-6.121M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 bg-[var(--surface)] border-[var(--border)] text-[#00dfd8] focus:ring-[#00dfd8] rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-[var(--muted)]">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-[#00dfd8] hover:text-[#007cf0] transition-colors hover:-translate-y-0.5">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="w-full py-3 rounded-2xl text-sm font-semibold 
                               bg-gradient-to-r from-[#007cf0] to-[#00dfd8] 
                               hover:opacity-90 transition-all 
                               hover:shadow-[0_0_15px_#00dfd8] hover:-translate-y-0.5">
                    Log In
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center text-[var(--muted)] text-sm">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="text-[#00dfd8] font-semibold hover:text-[#007cf0] transition-colors hover:-translate-y-0.5">
                    Create Account
                </a>
            </div>
        </div>

        <!-- Back to Home outside the container -->
        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-xs text-[var(--muted)] hover:text-[var(--text)] transition-colors hover:-translate-y-0.5">
                ← Back to Home
            </a>
        </div>

    </div>
</x-guest-layout>

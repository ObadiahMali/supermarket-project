<x-guest-layout>
    <style>
        .login-wrapper {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: white;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.08);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .login-container input {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
        }

        .login-container input::placeholder {
            color: #e0e0e0;
        }

        .login-container .btn {
            background: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            width: 100%;
        }

        .login-container a {
            color: #f9d423;
            text-decoration: underline;
        }
    </style>

    <div class="login-wrapper">
        <div class="login-container">
            <h1>Login</h1>

            <x-auth-session-status class="mb-4 text-danger" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <x-text-input id="email" type="email" name="email" :value="old('email')" placeholder="Email" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />

                <x-text-input id="password" type="password" name="password" placeholder="Password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />

                <div class="form-check text-start mb-3">
               
                    <label for="remember_me" class="form-check-label">Remember me</label>
                </div>

                @if (Route::has('password.request'))
                    <div class="mb-3 text-center">
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    </div>
                @endif

                <button type="submit" class="btn">Login</button>
            </form>

            <p class="mt-3 text-center">Don't have an account? <a href="{{ route('register') }}">Signup here</a>.</p>
        </div>
    </div>
</x-guest-layout>
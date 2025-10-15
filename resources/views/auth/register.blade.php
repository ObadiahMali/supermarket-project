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

        .login-container input,
        .login-container select {
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

        .login-container select option {
            background-color: #1e3c72;
            color: white;
        }

        .login-container .btn {
            background: #28a745;
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
            <h1>Register</h1>

            <x-auth-session-status class="mb-4 text-danger" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <x-text-input id="name" type="text" name="name" :value="old('name')" placeholder="Name" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />

                <!-- Email -->
                <x-text-input id="email" type="email" name="email" :value="old('email')" placeholder="Email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />

                <!-- Password -->
                <x-text-input id="password" type="password" name="password" placeholder="Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />

                <!-- Confirm Password -->
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />

                <!-- Role -->
                <select name="role" id="role" required>
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2 text-danger" />

                <button type="submit" class="btn">Register</button>
            </form>

            <p class="mt-3 text-center">Already have an account? <a href="{{ route('login') }}">Login here</a>.</p>
        </div>
    </div>
</x-guest-layout>

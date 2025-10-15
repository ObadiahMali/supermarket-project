<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.welcome-container {
    width: 90%;
    max-width: 600px;
    background: rgba(255, 255, 255, 0.08);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
    text-align: center;
    animation: fadeIn 0.8s ease-in-out;
}

.logo {
    width: 120px;
    height: auto;
    margin-bottom: 1rem;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
}

h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(to right, #f9d423, #ff4e50);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    color: rgba(255, 255, 255, 0.85);
}

.nav-links a {
    margin: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    color: white;
    background: linear-gradient(135deg, #007bff, #0056b3);
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.5);
    transition: all 0.3s ease;
    display: inline-block;
}

.nav-links a:hover {
    background: linear-gradient(135deg, #0056b3, #007bff);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.7);
}

footer {
    margin-top: 2rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
}

footer a {
    color: #f9d423;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 1.8rem;
    }
    p {
        font-size: 1rem;
    }
    .nav-links a {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
}
    </style>
</head>
<body>
    <div class="welcome-container">
        <img src="{{ asset('logo.jpg')}}" alt="Mali's Supermarket Logo" class="logo">

        <h1>Welcome to Mali's Supermarket</h1>
        <p>Where quality meets affordability. Please log in or register to continue.</p>

        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            @endif
        </div>

        <footer>
            <p>&copy; 2025 <a href="#">INTISAR ENT TECH</a>.</p>
        </footer>
    </div>
</body>
</html>

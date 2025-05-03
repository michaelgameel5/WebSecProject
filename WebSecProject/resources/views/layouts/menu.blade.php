<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <style>
        body { background: #eaf2fb; margin: 0; }
        .navbar-custom {
            background: #f8fafc;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            margin-bottom: 2.5rem;
        }
        .navbar-custom ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            margin: 0;
            padding: 0;
        }
        .navbar-custom a, .navbar-custom button {
            color: #222;
            text-decoration: none;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.25rem 0.5rem;
            transition: color 0.2s;
        }
        .navbar-custom a:hover, .navbar-custom button:hover {
            color: #2563eb;
        }
    </style>
</head>
<body>
<nav class="navbar-custom">
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('products.index') }}">Online Store</a></li>
    </ul>
    <ul>
        @auth
            <li><a href="{{ route('profile') }}">{{ auth()->user()->name }}</a></li>
            <li>
                <form method="POST" action="{{ route('do_logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @endauth
    </ul>
</nav>
</body>
</html>


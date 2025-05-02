<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light mb-8">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.index') }}">Online Store</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            @auth
                {{-- <a href="{{ route('password.change') }}" class="btn btn-warning">Change Password</a> --}}
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->name }}</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('do_logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-link bg-transparent border-0 p-0">Logout</button>
                    </form>
                </li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>
</body>
</html>


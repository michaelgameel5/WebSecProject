<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('even') }}">Even</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('prime') }}">Prime</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('multable') }}">Multiplication</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('bill') }}">Bill</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('trans') }}">Transcript</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('items') }}">Items</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('calculator') }}">Calculator</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('gpacalc') }}">GPA Calculator</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users_index') }}">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('questions.list') }}">MCQ Exam</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products_list') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('books.index') }}">Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tasks_index') }}">Tasks</a>
            </li>
        </ul>
        
        <ul class="navbar-nav">
            @auth
                <a href="{{ route('password.change') }}" class="btn btn-warning">Change Password</a>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->name }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('do_logout')}}">Logout</a></li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Register</a></li>
            @endauth
        </ul>
    </div>
</nav>
</body>
</html>


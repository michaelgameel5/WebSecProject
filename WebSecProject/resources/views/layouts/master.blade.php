<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - Online Store</title>
        <!-- Bootstrap CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Custom CSS -->
        <style>
            :root {
                --primary-color: #4a90e2;
                --secondary-color: #2c3e50;
                --accent-color: #e74c3c;
                --light-bg: #f8f9fa;
                --dark-bg: #343a40;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: var(--light-bg);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .navbar {
                background-color: white !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 1rem 0;
            }

            .navbar-brand {
                font-weight: bold;
                color: var(--primary-color) !important;
                font-size: 1.5rem;
            }

            .nav-link {
                color: var(--secondary-color) !important;
                font-weight: 500;
                padding: 0.5rem 1rem !important;
                transition: color 0.3s ease;
            }

            .nav-link:hover {
                color: var(--primary-color) !important;
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: darken(var(--primary-color), 10%);
                border-color: darken(var(--primary-color), 10%);
            }

            .card {
                border: none;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: transform 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            .container {
                padding: 2rem 0;
            }

            footer {
                background-color: var(--dark-bg);
                color: white;
                padding: 2rem 0;
                margin-top: auto;
            }

            .alert {
                border: none;
                border-radius: 8px;
            }

            .table {
                background-color: white;
                border-radius: 8px;
                overflow: hidden;
            }

            .table thead th {
                background-color: var(--primary-color);
                color: white;
                border: none;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-store me-2"></i>Online Store
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="fas fa-box me-1"></i>Products
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('do_logout') }}">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Register
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow-1">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Online Store</h5>
                        <p>Your one-stop shop for all your needs.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5>Contact Us</h5>
                        <p>
                            <i class="fas fa-envelope me-2"></i>support@onlinestore.com<br>
                            <i class="fas fa-phone me-2"></i>+1 234 567 890
                        </p>
                    </div>
                </div>
                <hr class="mt-4">
                <div class="text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Online Store. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Bootstrap Bundle with Popper -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>

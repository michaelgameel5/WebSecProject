<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Online Store')</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <style>
            body {
                background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
                font-family: 'Inter', Arial, sans-serif;
                min-height: 100vh;
            }
            .main-card {
                background: #fff;
                border-radius: 1rem;
                box-shadow: 0 4px 24px rgba(0,0,0,0.08);
                padding: 2.5rem 2.5rem 2rem 2.5rem;
                margin-top: 2.5rem;
                margin-bottom: 2.5rem;
            }
            .btn, button[type="submit"], input[type="submit"] {
                @apply btn;
            }
            .btn-primary, button.btn-primary, input.btn-primary {
                @apply btn-primary;
            }
            .btn-secondary, button.btn-secondary, input.btn-secondary {
                @apply btn-secondary;
            }
            /* Fallback for @apply if not using Tailwind JIT */
            .btn, button[type="submit"], input[type="submit"] {
                display: inline-block;
                font-weight: 600;
                text-align: center;
                vertical-align: middle;
                user-select: none;
                border: 1px solid transparent;
                padding: 0.5rem 1.25rem;
                font-size: 1rem;
                line-height: 1.5;
                border-radius: 0.375rem;
                transition: color 0.15s, background-color 0.15s, border-color 0.15s, box-shadow 0.15s;
            }
            .btn-primary, button.btn-primary, input.btn-primary {
                color: #fff;
                background-color: #2563eb;
                border-color: #2563eb;
            }
            .btn-primary:hover, button.btn-primary:hover, input.btn-primary:hover {
                background-color: #1d4ed8;
                border-color: #1d4ed8;
            }
            .btn-secondary, button.btn-secondary, input.btn-secondary {
                color: #fff;
                background-color: #6b7280;
                border-color: #6b7280;
            }
            .btn-secondary:hover, button.btn-secondary:hover, input.btn-secondary:hover {
                background-color: #4b5563;
                border-color: #4b5563;
            }
        </style>
    </head>
    <body>
        @include('layouts.menu')
        <div class="container">
            <div class="main-card">
                @yield('content')
            </div>
        </div>
    </body>
</html>

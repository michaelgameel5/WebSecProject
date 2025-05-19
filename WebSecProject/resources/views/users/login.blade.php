@extends('layouts.master')
@section('title', 'Login Page')
@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Left side - Image -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="position-relative h-100" style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.9), rgba(44, 62, 80, 0.9)), url('https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'); background-size: cover; background-position: center;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5">
                                <h2 class="display-4 fw-bold mb-4">Welcome Back</h2>
                                <p class="lead text-center mb-5">Access your account to explore our latest electronic products and exclusive deals.</p>
                                <p class="mt-5">Don't have an account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 py-2">Create Account</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Login form -->
                    <div class="col-lg-6">
                        <div class="card-body p-lg-5 p-4">
                            <div class="text-center mb-4">
                                <h1 class="h3 fw-bold">Sign In to Electro Store</h1>
                                <p class="text-muted">Enter your credentials to access your account</p>
                            </div>
                            
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{$error}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                            
                            <form action="{{route('do_login')}}" method="post" class="needs-validation" novalidate>
                                {{ csrf_field() }}
                                
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required value="{{ old('email') }}">
                                    <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Email address</label>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                                    <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                                    <div class="invalid-feedback">Please enter your password.</div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <a href="{{ route('forgot_password') }}" class="text-primary text-decoration-none">Forgot Password?</a>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill mb-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                                
                                <div class="text-center">
                                    <p class="text-muted mb-3">Or sign in with</p>
                                    <a href="{{route('login_with_google')}}" class="btn btn-outline-secondary w-100 py-2 rounded-pill">
                                        <img src="{{ asset('images/google-logo.svg') }}" alt="Google" height="20" class="me-2">
                                        Continue with Google
                                    </a>
                                </div>
                            </form>
                            
                            <div class="d-lg-none text-center mt-4">
                                <p class="mb-2">Don't have an account?</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4">Create Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation script
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
@endsection


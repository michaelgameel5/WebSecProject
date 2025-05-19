@extends('layouts.master')
@section('title', 'Register Page')
@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Left side - Image -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="position-relative h-100" style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.9), rgba(44, 62, 80, 0.9)), url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'); background-size: cover; background-position: center;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5">
                                <h2 class="display-4 fw-bold mb-4">Join Electro Store</h2>
                                <p class="lead text-center mb-5">Create an account and explore our latest electronic products with exclusive member benefits.</p>
                                <p class="mt-5">Already have an account?</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 py-2">Sign In</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Registration form -->
                    <div class="col-lg-6">
                        <div class="card-body p-lg-5 p-4">
                            <div class="text-center mb-4">
                                <h1 class="h3 fw-bold">Create Your Account</h1>
                                <p class="text-muted">Fill in the details below to register</p>
                            </div>
                            
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{$error}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endforeach
                            
                            <form action="{{route('do_register')}}" method="post" class="needs-validation" novalidate>
                                {{ csrf_field() }}
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" placeholder="Your name" name="name" value="{{ old('name') }}" required>
                                    <label for="name"><i class="fas fa-user me-2 text-muted"></i>Full Name</label>
                                    <div class="invalid-feedback">Please enter your name.</div>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" value="{{ old('email') }}" required>
                                    <label for="email"><i class="fas fa-envelope me-2 text-muted"></i>Email Address</label>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                                    <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                                    <div class="invalid-feedback">Please enter a password.</div>
                                </div>
                                
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" required>
                                    <label for="password_confirmation"><i class="fas fa-key me-2 text-muted"></i>Confirm Password</label>
                                    <div class="invalid-feedback">Please confirm your password.</div>
                                </div>
                                
                                <div class="mb-3 small text-muted">
                                    <p>Password must be at least 8 characters and include numbers, uppercase, lowercase letters, and symbols.</p>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill mb-3">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                                
                                <div class="d-lg-none text-center mt-4">
                                    <p class="mb-2">Already have an account?</p>
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Sign In</a>
                                </div>
                            </form>
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


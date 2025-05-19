@extends('layouts.master')

@section('title', 'Welcome')

@section('content')
    <!-- Hero Section -->
    <div class="row hero-section mb-5">
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <h1 class="display-4 fw-bold">Welcome to Electro Store</h1>
            <p class="lead my-4">Discover the latest electronics and tech gadgets at competitive prices. Quality products delivered to your doorstep.</p>
            <div class="d-flex gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4">Shop Now</a>
            </div>
        </div>
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="img-fluid rounded shadow" alt="Electronics">
        </div>
    </div>




    <!-- Benefits Section -->
    <section class="mb-5 py-5 bg-light rounded">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Why Choose Us</h2>
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                        <h5>Free Shipping</h5>
                        <p class="text-muted">On orders over $50</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                        <h5>Easy Returns</h5>
                        <p class="text-muted">30-day return policy</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Secure Payments</h5>
                        <p class="text-muted">Protected transactions</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>24/7 Support</h5>
                        <p class="text-muted">Dedicated support team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="mb-5">
        <h2 class="fw-bold mb-4">What Our Customers Say</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex mb-3">
                            <div class="text-warning me-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-muted">5.0</span>
                        </div>
                        <p class="mb-4">"Excellent service and quality products. I've been a repeat customer for years and have never been disappointed. The delivery is always on time."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle me-3" width="50" height="50" alt="Customer">
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Loyal Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex mb-3">
                            <div class="text-warning me-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-muted">5.0</span>
                        </div>
                        <p class="mb-4">"The customer service is exceptional. When I had an issue with my order, they resolved it immediately. The products are high quality and well-priced."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/men/44.jpg" class="rounded-circle me-3" width="50" height="50" alt="Customer">
                            <div>
                                <h6 class="mb-0">Michael Thompson</h6>
                                <small class="text-muted">Happy Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@extends('layouts.master')
@section('title', 'Profile')
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Your Profile</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>ID:</strong> {{ $user->id }}</li>
        <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
    </ul>
</div>
@endsection
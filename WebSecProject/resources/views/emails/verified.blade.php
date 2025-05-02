@extends('layouts.master')
@section('title', 'Verification Email')
@section('content')

    <div class="row">
        <div class="m-4 col-sm-6">
            <div class="alert alert-success">
                <strong>Congratulation!</strong> Dear {{$user->name}},
                 your email {{$user->email}} has been verified successfully.
            </div>
        </div>
    </div>
 
@endsection

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>Please click the link below to verify your email address:</p>
    <a href="{{ $link }}">{{ $link }}</a>
    <p>If you did not register, please ignore this email.</p>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome</div>
                <div class="card-body">
                    @auth
                        <h1>Welcome, {{ auth()->user()->name }}</h1>
                        <p>Your email is: {{ auth()->user()->email }}</p>
                        <p>You have successfully logged in.</p>
                    @endauth
                    @guest
                        <h1>Welcome, guest</h1>
                    @endguest
                    <div class="text-center">
                        <a href="{{ route('users') }}" class="btn btn-primary">Users</a>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

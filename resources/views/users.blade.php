<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item {{ ($users->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $users->url(1) }}">Previous</a>
            </li>
            @for ($i = 1; $i <= $users->lastPage(); $i++)
                <li class="page-item {{ ($users->currentPage() == $i) ? ' active' : '' }}">
                    <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ ($users->currentPage() == $users->lastPage()) ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $users->url($users->currentPage()+1) }}">Next</a>
            </li>
        </ul>
    </nav>
</div>
</body>
</html>

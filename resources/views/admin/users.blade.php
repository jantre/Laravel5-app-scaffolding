@extends('layout')

@section('content')
@if (empty($users))
    There are no users
@else
    Total users: {{ $total }}
    <div class="col-lg-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Status</th>
                <th>Created</th>
                <th>Last Login</th>
                <th>Roles</th>
            </tr>
            </thead>
            <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user['id'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>{{ $user['username'] }}</td>
            <td>{{ $user['status'] }}</td>
            <td>{{ $user['created_at'] }}</td>
            <td>{{ $user['updated_at'] }}</td>
            <td>{{ $user['roles'] }}</td>
        </tr>
    @endforeach
            </tbody>
        </table>
    </div>
    {!! $pagination !!}
@endif
@stop

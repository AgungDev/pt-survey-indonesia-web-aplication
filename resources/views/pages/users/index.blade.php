@extends('layouts.app')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <button type="button" class="btn btn-primary" id="toggleAddUserForm">+ Tambah User</button>
        <div class="mt-2 d-none" id="addUserForm">
            <div class="card card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select name="role_id" class="form-select">
                                <option value="">-- None --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="password" name="password" class="form-control" placeholder="Password" required minlength="8">
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required minlength="8">
                        </div>
                        <div class="col-md-4 mb-2">
                            <button class="btn btn-success">Buat User</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ optional($user->role)->name ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-user-toggle" data-target="#editUser{{ $user->id }}">Edit</button>
                            <div class="mt-2 d-none" id="editUser{{ $user->id }}">
                                <form method="POST" action="{{ route('users.update', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <select name="role_id" class="form-select">
                                                <option value="">-- None --</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" @if(optional($user->role)->id == $role->id) selected @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button class="btn btn-sm btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                                <details class="mt-2">
                                    <summary class="btn btn-sm btn-warning">Change password</summary>
                                    <form method="POST" action="{{ route('users.updatePassword', $user->id) }}" class="mt-2">
                                        @csrf
                                        <div class="mb-2">
                                            <input type="password" name="password" class="form-control" placeholder="New password" required minlength="8">
                                        </div>
                                        <div class="mb-2">
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required minlength="8">
                                        </div>
                                        <button class="btn btn-sm btn-success">Update password</button>
                                    </form>
                                </details>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToggle = document.getElementById('toggleAddUserForm');
            const addForm = document.getElementById('addUserForm');

            if (addToggle && addForm) {
                addToggle.addEventListener('click', function() {
                    addForm.classList.toggle('d-none');
                });
            }

            document.querySelectorAll('.edit-user-toggle').forEach(function(button) {
                button.addEventListener('click', function() {
                    const target = button.getAttribute('data-target');
                    if (!target) return;
                    const panel = document.querySelector(target);
                    if (!panel) return;
                    panel.classList.toggle('d-none');
                });
            });
        });
    </script>
@endsection

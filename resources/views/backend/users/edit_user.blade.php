@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Edit User</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $userRole && $userRole->id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

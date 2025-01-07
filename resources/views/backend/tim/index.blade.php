@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection

@section('script_atas')
@endsection

@section('script_bawah')
@endsection

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-lg-12">
            <h2>Daftar Pengembang</h2>
            <a href="{{ route('pengembang.create') }}" class="btn btn-primary">Tambah Pengembang</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Keahlian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($developers as $key => $developer)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if($developer->photo)
                                        <img src="{{ asset('storage/'.$developer->photo) }}" alt="Photo" width="50">
                                    @else
                                        <span>No Photo</span>
                                    @endif
                                </td>
                                <td>{{ $developer->name }}</td>
                                <td>{{ str_replace('|', ' | ', $developer->skill) }}</td>
                                <td>
                                    <a href="{{ route('pengembang.show', $developer->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('pengembang.edit', $developer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('pengembang.destroy', $developer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

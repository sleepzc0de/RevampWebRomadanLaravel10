@extends('layouts.webromadan_backend.master_layout')

@section('css')
@endsection

@section('script_atas')
@endsection

@section('script_bawah')
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pengembang</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-lg-3">Foto</label>
                        <div class="col-lg-9">
                            @if($developer->photo)
                                <img data-blob-src="{{ route('media.blob', ['photos', basename($developer->photo)]) }}" alt="Developer Photo" class="img-fluid" style="max-width: 200px; background:#eef1f4;">
                            @else
                                <span>Tidak ada foto</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3">Nama</label>
                        <div class="col-lg-9">
                            {{ $developer->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-lg-3">Keahlian</label>
                        <div class="col-lg-9">
                            {{ str_replace('|', ' | ', $developer->skill) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-9 offset-lg-3">
                            <a href="{{ route('pengembang.edit', $encryptedId) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('pengembang.index') }}" class="btn btn-link">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
                    <h4>Tambah Pengembang Baru</h4>
                </div>
                <div class="card-body">
                    <form class="form-validate-jquery" action="{{ route('pengembang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Nama <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input maxlength="100"
                                type="text" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="Masukkan nama pengembang">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Keahlian <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <div id="skill-container">
                                    <div class="input-group mb-2">
                                        <input maxlength="100" type="text" name="skill[]" class="form-control @error('skill.0') is-invalid @enderror" required placeholder="Masukkan keahlian">
                                        <button type="button" class="btn btn-success add-skill">+</button>
                                    </div>
                                </div>
                                @error('skill.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Foto</label>
                            <div class="col-lg-9">
                                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-9 offset-lg-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('pengembang.index') }}" class="btn btn-link">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambah skill
    document.querySelector('.add-skill').addEventListener('click', function() {
        const container = document.getElementById('skill-container');
        const newSkill = document.createElement('div');
        newSkill.className = 'input-group mb-2';
        newSkill.innerHTML = `
            <input type="text" name="skill[]" class="form-control" required placeholder="Masukkan keahlian">
            <button type="button" class="btn btn-danger remove-skill">-</button>
        `;
        container.appendChild(newSkill);
    });

    // Hapus skill
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-skill')) {
            e.target.parentElement.remove();
        }
    });
});
</script>
@endpush
@endsection

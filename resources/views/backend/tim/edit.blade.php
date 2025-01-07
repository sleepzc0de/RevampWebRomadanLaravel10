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
                    <h4>Edit Pengembang</h4>
                </div>
                <div class="card-body">
                    <form class="form-validate-jquery" action="{{ route('pengembang.update', $developer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Nama <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" name="name" value="{{ old('name', $developer->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Keahlian <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <div id="skill-container">
                                    @foreach(explode('|', $developer->skill) as $index => $skill)
                                    <div class="input-group mb-2">
                                        <input type="text" name="skill[]" value="{{ old('skill.'.$index, $skill) }}" class="form-control @error('skill.'.$index) is-invalid @enderror" required placeholder="Masukkan keahlian">
                                        @if($loop->first)
                                            <button type="button" class="btn btn-success add-skill">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger remove-skill">-</button>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @error('skill.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Foto</label>
                            <div class="col-lg-9">
                                @if($developer->photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$developer->photo) }}" alt="Current Photo" width="100">
                                    </div>
                                @endif
                                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-9 offset-lg-3">
                                <button type="submit" class="btn btn-primary">Update</button>
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

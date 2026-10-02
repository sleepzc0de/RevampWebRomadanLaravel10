@if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" x-transition.opacity>
        <h4 class="alert-heading d-flex align-items-center"><i class="ph-check-circle me-2 text-lg"></i>Berhasil</h4>
        <p class="mb-0">{{ $message }}</p>
        <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
    </div>
@endif

@if($message = Session::get('failed'))
    <div class="alert alert-danger alert-dismissible" role="alert" x-data="{ show: true }" x-show="show" x-transition.opacity>
        <h4 class="alert-heading d-flex align-items-center"><i class="ph-warning-circle me-2 text-lg"></i>Gagal</h4>
        <p class="mb-0">{{ $message }}</p>
        <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
    </div>
@endif

{{-- Error validasi form: ditampilkan sebagai daftar di sini agar tetap terlihat
     pada form yang tidak memakai komponen <x-form.*> (mis. modal, form kustom). --}}
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert" x-data="{ show: true }" x-show="show" x-transition.opacity>
        <h4 class="alert-heading d-flex align-items-center"><i class="ph-warning-circle me-2 text-lg"></i>Periksa kembali isian Anda</h4>
        <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
    </div>
@endif

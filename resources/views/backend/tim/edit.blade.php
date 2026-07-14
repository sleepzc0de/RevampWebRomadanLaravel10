@extends('layouts.webromadan_backend.master_layout')

@php
    $existingSkills = $developer->skill ? explode('|', $developer->skill) : [''];
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Tim Pengembang</h5>
    </div>

    @include('layouts.webromadan_backend.session_notif')

    <form action="{{ route('pengembang.update', Crypt::encrypt($developer->id)) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-form.field name="name" label="Nama" :value="$developer->name" required maxlength="255" />

            <div class="mb-4" x-data="{ skills: @js($existingSkills) }">
                <label class="form-label mb-1.5 block">Keahlian <span class="text-danger">*</span></label>
                <template x-for="(skill, i) in skills" :key="i">
                    <div class="mb-2 flex gap-2">
                        <input type="text" name="skill[]" x-model="skills[i]" class="form-control" placeholder="Contoh: Laravel">
                        <button type="button" x-show="skills.length > 1" @click="skills.splice(i, 1)" class="btn btn-outline-danger btn-icon">&minus;</button>
                        <button type="button" x-show="i === skills.length - 1" @click="skills.push('')" class="btn btn-outline-primary btn-icon">+</button>
                    </div>
                </template>
                @error('skill')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <x-form.file name="photo" label="Foto" accept="image/jpeg,image/png,image/jpg,image/gif" />
            @if($developer->photo)
                <div class="mb-4 -mt-3">
                    <img data-blob-src="{{ route('media.blob', ['photos', basename($developer->photo)]) }}" alt="" class="img-thumbnail" style="width: 150px; background:#eef1f4;">
                </div>
            @endif
        </div>

        <x-form.actions :back-route="route('pengembang.index')" submit-label="Update" />
    </form>
</div>
@endsection

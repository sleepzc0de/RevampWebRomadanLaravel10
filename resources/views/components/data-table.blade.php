@props([
    'ajax',
    'columns',
    'title' => null,
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'order' => [0, 'asc'],
    'perPage' => 10,
    'searchPlaceholder' => 'Cari data...',
])

@php
    $columnsJson = collect($columns)->map(fn ($c) => [
        'data' => $c['data'],
        'name' => $c['name'] ?? $c['data'],
        'orderable' => $c['orderable'] ?? true,
        'searchable' => $c['searchable'] ?? true,
    ])->values()->all();
@endphp

<div
    x-data="serverTable({
        ajaxUrl: @js($ajax),
        columns: @js($columnsJson),
        order: @js($order),
        perPage: {{ (int) $perPage }},
    })"
    class="card"
>
    <div class="card-header flex flex-wrap items-center justify-between gap-3">
        @if($title)
            <h5 class="mb-0">{{ $title }}</h5>
        @endif

        <div class="ms-auto flex flex-wrap items-center gap-2">
            {{ $headerActions ?? '' }}

            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn btn-primary">
                    <i class="ph-plus"></i> {{ $createLabel }}
                </a>
            @endif
        </div>
    </div>

    {{ $notice ?? '' }}

    <div class="datatable-header">
        <div class="form-control-feedback form-control-feedback-end min-w-[220px] flex-1">
            <input type="text" x-model="search" placeholder="{{ $searchPlaceholder }}"
                   class="form-control" @input.debounce.300ms="page = 0; load()">
            <div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div>
        </div>

        {{ $toolbar ?? '' }}

        <div class="ms-sm-auto flex items-center gap-2 text-sm">
            <span>Tampilkan:</span>
            <select class="form-select form-select-sm" x-model.number="perPage" @change="page = 0; load()">
                <template x-for="n in [10, 25, 50, 100]" :key="n">
                    <option :value="n" x-text="n"></option>
                </template>
            </select>
        </div>
    </div>

    <div class="datatable-scroll relative">
        <div x-show="loading" x-cloak class="dataTables_processing">Memuat...</div>

        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    @foreach($columns as $i => $col)
                        <th
                            @if($col['orderable'] ?? true)
                                @click="sortBy({{ $i }})"
                                :class="sortCol === {{ $i }} ? (sortDir === 'asc' ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                            @endif
                            class="{{ $col['class'] ?? '' }}"
                        >{{ $col['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <template x-if="!loading && rows.length === 0">
                    <tr><td colspan="{{ count($columns) }}" class="dataTables_empty">Tidak ada data yang cocok</td></tr>
                </template>
                <template x-for="(row, idx) in rows" :key="row.DT_RowId ?? idx">
                    <tr>
                        @foreach($columns as $col)
                            @if($col['image'] ?? false)
                                <td class="{{ $col['class'] ?? '' }}">
                                    <img :src="col(row, '{{ $col['data'] }}') || {{ Illuminate\Support\Js::from($col['fallback'] ?? '') }}" class="img-thumbnail h-12 w-12 object-cover" alt="">
                                </td>
                            @elseif($col['raw'] ?? false)
                                <td class="{{ $col['class'] ?? '' }}" x-html="col(row, '{{ $col['data'] }}')"></td>
                            @else
                                <td class="{{ $col['class'] ?? '' }}" x-text="col(row, '{{ $col['data'] }}')"></td>
                            @endif
                        @endforeach
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div class="datatable-footer">
        <p class="dataTables_info" x-text="infoText"></p>

        <div class="dataTables_paginate" x-show="totalPages > 1" x-cloak>
            <button type="button" class="paginate_button" :class="{ disabled: page === 0 }" @click="goTo(0)">&laquo;</button>
            <button type="button" class="paginate_button" :class="{ disabled: page === 0 }" @click="goTo(page - 1)">&larr;</button>
            <template x-for="p in pageNumbers" :key="p">
                <button type="button" class="paginate_button" :class="{ current: p === page }" @click="goTo(p)" x-text="p + 1"></button>
            </template>
            <button type="button" class="paginate_button" :class="{ disabled: page >= totalPages - 1 }" @click="goTo(page + 1)">&rarr;</button>
            <button type="button" class="paginate_button" :class="{ disabled: page >= totalPages - 1 }" @click="goTo(totalPages - 1)">&raquo;</button>
        </div>
    </div>
</div>

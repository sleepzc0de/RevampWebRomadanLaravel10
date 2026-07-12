@props(['icon' => 'fa-inbox', 'title' => 'Belum ada data', 'text' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-200 bg-slate-50 px-6 py-16 text-center dark:border-white/10 dark:bg-navy-900']) }}>
    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-300 shadow-sm dark:bg-navy-800 dark:text-slate-600">
        <i class="fa-solid {{ $icon }} text-xl"></i>
    </span>
    <h3 class="mt-4 text-base font-bold text-slate-600 dark:text-slate-300">{{ $title }}</h3>
    @if ($text)
        <p class="mt-1 max-w-sm text-sm text-slate-400 dark:text-slate-500">{{ $text }}</p>
    @endif
</div>

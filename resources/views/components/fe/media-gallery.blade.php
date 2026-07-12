@props(['items' => [], 'ratio' => 'aspect-[16/10]'])

@if (count($items))
<div x-data="mediaGallery(@js($items))">
    {{-- Viewport --}}
    <div class="relative overflow-hidden rounded-2xl bg-slate-100 {{ $ratio }} shadow-[var(--shadow-soft)] dark:bg-white/5">
        <template x-for="(item, i) in items" :key="i">
            <div x-show="active === i" x-transition.opacity.duration.400ms class="absolute inset-0">
                <template x-if="item.type === 'image'">
                    <button type="button" @click="openLightbox(i)" class="block h-full w-full cursor-zoom-in">
                        <img :src="item.src" :alt="item.alt" class="h-full w-full object-cover" loading="lazy">
                    </button>
                </template>
                <template x-if="item.type === 'video'">
                    <iframe :src="item.embedUrl" class="h-full w-full"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </template>
            </div>
        </template>

        <button type="button" @click="prev" x-show="items.length > 1"
            class="absolute top-1/2 left-3 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-navy-800 shadow-md transition hover:bg-white dark:bg-navy-800/90 dark:text-white dark:hover:bg-navy-800">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>
        <button type="button" @click="next" x-show="items.length > 1"
            class="absolute top-1/2 right-3 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-navy-800 shadow-md transition hover:bg-white dark:bg-navy-800/90 dark:text-white dark:hover:bg-navy-800">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>

        <div class="absolute right-3 bottom-3 flex gap-1.5" x-show="items.length > 1">
            <template x-for="(item, i) in items" :key="'dot'+i">
                <button type="button" @click="go(i)" :aria-current="active === i"
                    :class="active === i ? 'w-5 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/80'"
                    class="h-1.5 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>

    {{-- Thumbnail --}}
    <div class="no-scrollbar mt-3 flex gap-2 overflow-x-auto" x-show="items.length > 1">
        <template x-for="(item, i) in items" :key="'thumb'+i">
            <button type="button" @click="go(i)"
                :class="active === i ? 'ring-2 ring-brand-700 opacity-100' : 'ring-1 ring-slate-200 opacity-60 hover:opacity-100 dark:ring-white/10'"
                class="relative h-16 w-20 flex-none overflow-hidden rounded-lg transition">
                <template x-if="item.type === 'image'">
                    <img :src="item.src" :alt="item.alt" class="h-full w-full object-cover">
                </template>
                <template x-if="item.type === 'video'">
                    <span class="flex h-full w-full items-center justify-center bg-navy-900 text-white">
                        <i class="fa-solid fa-play text-xs"></i>
                    </span>
                </template>
            </button>
        </template>
    </div>

    {{-- Lightbox (gambar saja) --}}
    <template x-teleport="body">
        <div x-show="lightbox" x-cloak @keydown.escape.window="lightbox = false"
            x-transition.opacity
            class="fixed inset-0 z-100 flex items-center justify-center bg-navy-950/92 p-4 backdrop-blur-sm">
            <button type="button" @click="lightbox = false"
                class="absolute top-5 right-5 flex h-11 w-11 items-center justify-center rounded-full text-white/80 transition hover:bg-white/10 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <img :src="current?.src" :alt="current?.alt" @click.outside="lightbox = false"
                class="max-h-[85vh] max-w-full rounded-lg object-contain shadow-2xl">
        </div>
    </template>
</div>
@endif

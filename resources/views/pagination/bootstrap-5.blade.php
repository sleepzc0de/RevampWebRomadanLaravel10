@if ($paginator->hasPages())
                <ul class="pagination pagination-lg">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item ml-3 disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link page-link-romadan" aria-hidden="true"><i class="fa-solid fa-arrow-left"></i></span>
                        </li>
                    @else
                        <li class="page-item ml-3">
                            <a class="page-link page-link-romadan" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i class="fa-solid fa-arrow-left"></i></a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item ml-3 disabled" aria-disabled="true"><span class="page-link page-link-romadan">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item ml-3 active" aria-current="page"><span class="page-link page-link-romadan">{{ $page }}</span></li>
                                @else
                                    <li class="page-item ml-3"><a class="page-link page-link-romadan" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item ml-3">
                            <a class="page-link page-link-romadan" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i class="fa-solid fa-arrow-right"></i></a>
                        </li>
                    @else
                        <li class="page-item ml-3 disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link page-link-romadan" aria-hidden="true"><i class="fa-solid fa-arrow-right"></i></span>
                        </li>
                    @endif
                </ul>
            
@endif

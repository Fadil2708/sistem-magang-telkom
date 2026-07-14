@props(['paginator' => null])

@if($paginator && $paginator->hasPages())
    <div class="pagination-wrap">
        <nav style="display:flex;align-items:center;gap:4px">
            {{-- Previous --}}
            @if($paginator->onFirstPage())
                <span class="action-btn" style="opacity:0.4;cursor:default">
                    <i class="ti ti-chevron-left"></i>
                </span>
            @else
                <a href="{{ url($paginator->previousPageUrl()) }}" class="action-btn" wire:key="prev">
                    <i class="ti ti-chevron-left"></i>
                </a>
            @endif

            {{-- Pages --}}
            @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                <a href="{{ url($url) }}" wire:key="page-{{ $page }}"
                   style="min-width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s;
                    {{ $page === $paginator->currentPage()
                         ? 'background:#2563EB;color:#fff'
                        : 'background:#F5F4F2;color:#5C5A55;border:1px solid #E8E6E1' }}">
                    {{ $page }}
                </a>
            @endforeach

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <a href="{{ url($paginator->nextPageUrl()) }}" class="action-btn" wire:key="next">
                    <i class="ti ti-chevron-right"></i>
                </a>
            @else
                <span class="action-btn" style="opacity:0.4;cursor:default">
                    <i class="ti ti-chevron-right"></i>
                </span>
            @endif
        </nav>
    </div>
@endif

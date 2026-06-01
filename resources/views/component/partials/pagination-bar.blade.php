@php
    $currentPage = method_exists($paginator, 'currentPage') ? $paginator->currentPage() : 1;
    $lastPage    = method_exists($paginator, 'lastPage')    ? $paginator->lastPage()    : 1;
@endphp

<div id="pagination-bar" class="mt-20 flex items-center justify-center gap-8 transition-all duration-500 {{ $lastPage <= 1 ? 'opacity-0 pointer-events-none' : '' }}">
    
    <button id="btn-prev"
        onclick="changePage(currentPage - 1)"
        class="group flex items-center gap-3 text-[10px] uppercase tracking-[0.4em] transition-all duration-300
               {{ $currentPage <= 1 ? 'opacity-30 pointer-events-none' : 'text-gray-400 hover:text-gold' }}">
        <svg class="w-4 h-4 rotate-180 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
        Prev
    </button>

    <div class="flex items-center gap-3 font-serif italic text-gray-400">
        <span id="page-current">{{ str_pad($currentPage, 2, '0', STR_PAD_LEFT) }}</span>
        <span class="text-gold/30 text-xs">/</span>
        <span id="page-total">{{ str_pad($lastPage, 2, '0', STR_PAD_LEFT) }}</span>
    </div>

    <button id="btn-next"
        onclick="changePage(currentPage + 1)"
        class="group flex items-center gap-3 text-[10px] uppercase tracking-[0.4em] transition-all duration-300
               {{ $currentPage >= $lastPage ? 'opacity-30 pointer-events-none' : 'text-gray-400 hover:text-gold' }}">
        Next
        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
    </button>
</div>

<script>
    let currentPage = {{ $currentPage }};
    let lastPage    = {{ $lastPage }};
    let isFetching  = false;
</script>
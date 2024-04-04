<style>
    .page-item>a {


        color: #000000d1;
    }

    .page-link {
        color: #9E9E9E;

    }

    .disabled {
        color: #9E9E9E;
        cursor: not-allowed;
    }
</style>

@if (isset($paginator) && $paginator->hasPages())

    <ul class="pagination">
        {{-- Előző oldal --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-caret-left"></i></span></li>
        @else
            <li class="page-item"><a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i class="fa-solid fa-caret-left"></i></a></li>
        @endif

        {{-- Oldalszámok --}}
        @foreach (range(1, $paginator->lastPage()) as $page)
            @if ($page == $paginator->currentPage())
                <li class="page-item active disabled"><span class="page-link">{{ $page }}</span></li>
            @else
                <li class="page-item"><a href="{{ $paginator->url($page) }}" class="page-link">{{ $page }}</a></li>
            @endif
        @endforeach

        {{-- Következő oldal --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a href="{{ $paginator->nextPageUrl() }}" class="page-link"><i class="fa-solid fa-caret-right"></i></a></li>
        @else
            <li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-caret-right"></i></span></li>
        @endif
    </ul>
@endif

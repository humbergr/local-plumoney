@if ($paginator->hasPages())
    <nav aria-label="Paginate Transaction History">
        <ul class="pagination justify-content-center mt-4">

            <!-- Previous link -->
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <!-- Page number links -->
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                        @else
                            <li class="page-item">
                        @endif
                            <a class="page-link" href="{{$url}}">{{$page}}</a>
                        </li>
                    @endforeach
                @else
                    <li class="page-item mr-2 ml-2">
                        {{$element}}
                    </li>
                @endif
            @endforeach

            <!-- Next link -->
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @endif

        </ul>
    </nav>
@endif

@if($last_page > 1)
    <p class="pagenavy" id="table_pager">
        @if($prev_page_url)
            <a href="{{ $prev_page_url }}" class="prev"></a>
        @endif
        @for($i=1; $i <= $last_page; $i++)
            @if($current_page == $i)
                <span class="current">{{ $i }}</span>
            @else
                <a href="{{ $path }}?page={{ $i }}">{{ $i }}</a>
            @endif
        @endfor
        @if($next_page_url)
            <a href="{{ $next_page_url }}" class="next"></a>
        @endif
    </p>
@endif

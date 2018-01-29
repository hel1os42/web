@if($last_page > 1)
    <p class="pagenavy" id="table_pager">
        @if($prev_page_url)
            <a href="{{ $prev_page_url }}" class="prev"></a>
        @endif
        @php
            $from = (1 < $current_page - 20 ) ? $current_page - 20 : 1;
            $to = ($last_page > $current_page + 20 ) ? $current_page + 20 : $last_page;
        @endphp
        @for($i=$from; $i<=$to; $i++)
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

<br><br>
@if($last_page > 1)
    <ul class="pagination">
        @if($prev_page_url)
            <li class="page-item"><a class="page-link" href="{{$prev_page_url}}" rel="prev">prev</a></li>
        @endif
        @for($i=1; $i<=$last_page; $i++)
            @if($current_page == $i)
                <strong>{{$i}}</strong>
            @else
                {{$i}}
            @endif
            &nbsp;|&nbsp;
        @endfor
        @if($next_page_url)
            <li class="page-item"><a class="page-link" href="{{$next_page_url}}" rel="next">next</a></li>
        @endif
    </ul>
@endif
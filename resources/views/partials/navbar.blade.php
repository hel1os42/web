<nav class="navbar navbar-light bg-light">
    <div class="collapse navbar-collapse">
        @if(isset($leftLinksBar))
            <ul class="navbar-nav mr-auto" style="list-style: none; padding: 0 20px 0 0;">
                @foreach($leftLinksBar as $anchor)
                    <li class="nav-item">
                        <a href="{{$anchor['link']}}" class="btn">{!! $anchor['text'] !!}</a>
                    </li>
                @endforeach
            </ul>
        @endif
        @if(isset($title))
            <span class="navbar-brand mb-0 h1">{{$title}}</span>
        @endif
        @if(isset($rightLinksBar))
            <ul class="navbar-nav mr-auto" style="list-style: none; float:right;">
                @foreach($rightLinksBar as $anchor)
                    <li class="nav-item">
                        <a href="{{$anchor['link']}}" class="btn">{!! $anchor['text'] !!}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</nav>
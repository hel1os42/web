<div class="container">
    <div class="clearfix">
        <div class="logo pull-left">
            <a href="/"><img src="{{ asset('img/logo.png') }}" alt="nau.io"></a>
            <img id="kcolBdA" class="advert ads banner" src="/img/advert/banner.gif" alt="This must be hidden">
        </div>
        <script>
            /* AdBlock detector */
            window.addEventListener('load', function(){
                setTimeout(function(){
                    let ad =  document.getElementById('kcolBdA');
                    /* rules */
                    let hasInlineStyle = ad.getAttribute('style');
                    let isDisplayNone = getComputedStyle(ad).display === 'none';
                    let isNotVisible = getComputedStyle(ad).visibility === 'hidden';
                    let isTransparent = getComputedStyle(ad).opacity !== '1';
                    let test = !ad || hasInlineStyle || isDisplayNone || isNotVisible || isTransparent;
                    /* console */
                    console.groupCollapsed('Adblock detector');
                    console.log('img was removed: ', !ad, ad.outerHTML);
                    console.log('added styles: ', !!hasInlineStyle, ad.getAttribute('style'));
                    console.log('display none: ', isDisplayNone, getComputedStyle(ad).display);
                    console.log('visibility hidden: ', isNotVisible, getComputedStyle(ad).visibility);
                    console.log('transparent: ', isTransparent, 'opacity =', getComputedStyle(ad).opacity);
                    console.groupEnd();
                    /* notification */
                    if (test) alert('Please disable Adblock to work with NAU cabinet.');
                    //ad.parentElement.removeChild(ad);
                }, 1000);
            });
        </script>
        @auth
            <div class="controls pull-right">
                @if(!is_null(auth()->user()))
                    @if(auth()->user()->isImpersonated())
                            <a href="{{ route('stop_impersonate') }}" class="dropdown-toggle" style="width:auto">
                                <i class="fa fa-fighter-jet"></i>
                                Leave impersonation
                            </a>
                    @endif
                @endif
                <a href="{{ route('profile') }}" title="Pofile"><i class="fa fa-user-o"></i></a>
                <a href="{{ route('logout') }}" title="Logout"><i class="fa fa-sign-out"></i></a>
            </div>

            <div class="advert-name pull-right">
                {{ $authUser["name"] }}
            </div>

            <div class="advert-name pull-right">
                @if(isset($authUser["accounts"]["NAU"]["balance"]))
                Balance: <span style="color: #f08301;"><span id="header_nau_balance">{{ $authUser["accounts"]["NAU"]["balance"] }}</span> NAU</span>
                @else
                    The problem of getting a balance, contact us if this message does not disappear in the near future.
                    @endif
            </div>
        @endauth
    </div>
    @auth
        <nav>
            <menu>
                @include('role-partials.selector', ['partialRoute' => 'partials.header-menu'])
            </menu>
        </nav>
    @endauth
</div>

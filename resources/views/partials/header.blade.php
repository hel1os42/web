            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
                    		<i class="ti-arrow-left"></i>
                        </button>
                    </div>
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <span class="navbar-brand"> @yield('title') </span>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            @impersonating
                            <li>
                                <a href="{{ route('stop_impersonate') }}" class="dropdown-toggle">
                                    <i class="ti-shift-right"></i>
                                    <p>Leave impersonation</p>
                                </a>
                            </li>
                            @endImpersonating
                            <li>
                                @auth
                                <a href="{{route('profile')}}" class="dropdown-toggle">
                                    <i class="ti-user"></i>
                                    <p>Profile</p>
                                </a>
                                @endauth
                                @guest
                                <a href="{{route('loginForm')}}" class="dropdown-toggle">
                                    <p>Login</p>
                                    <i class="ti-shift-left"></i>
                                </a>
                                @endguest
                            </li>
                            @auth
							<li>
                                <a href="{{route('logout')}}" class="dropdown-toggle">
                                    <i class="ti-shift-right"></i>
                                    <p>Logout</p>
                                </a>
                            </li>
                            @endauth
                            <li class="separator hidden-lg hidden-md"></li>
                        </ul>
                    </div>
                </div>
            </nav>

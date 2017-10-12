<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <link href=" {{ asset('jquery/jquery-ui-1.9.2.custom.css') }} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('jquery/jquery.timepicker.css') }} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/bootstrap.min.css') }} " rel="stylesheet" type="text/css">
    <link href=" {{ asset('css/amaze.css') }} " rel="stylesheet" type="text/css">
    <!--link href=" {{ asset('css/base.css') }} " rel="stylesheet" type="text/css"-->
    <link href=" {{ asset('css/font-awesome.min.css') }} " rel="stylesheet">
    <link href=" {{ asset('css/font-muli.css') }} " rel='stylesheet' type='text/css'>
    <link href=" {{ asset('css/themify-icons.css') }} " rel="stylesheet">
</head>
<body>
<!--div class="container">
    @section('header')
        <div class="header">
            <div class="header-logo">NAU</div>
            <div class="header-right">
                @if (\Auth::check())
                    <a href="{{route('profile')}}">Profile</a> | <a href="{{route('logout')}}">Logout</a>
                @else
                    Hello guest! &nbsp; <a href="{{route('loginForm')}}">login</a>
                @endif
            </div>
        </div>
    @show
    @if (isset($errors))
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    {!! session()->has('message') ? '<div class="alert alert-info"><p>'.session()->get('message').'</p></div>' : '' !!}

    <div class="content">
        @yield('content')
    </div>
</div-->

<div class="wrapper">
    @section('sidebar')
		<div class="sidebar" data-background-color="brown" data-active-color="danger">
	    <!--
			Tip 1: you can change the color of the sidebar's background using: data-background-color="white | brown"
			Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
		-->
            <div class="logo">
                <a href="#" class="simple-text">
                    Amaze Admin
                </a>
            </div>
            <div class="logo logo-mini">
                <a href="#" class="simple-text">
                    A
                </a>
            </div>
            <div class="sidebar-wrapper">
				<ul class="nav">
                    <li class="active">
                        <a href="index.html">
                            <i class="ti-panel"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
					<li>
						<a href="widgets/widgets.html">
							<i class="ti-widget"></i>
							<p>Widgets</p>
						</a>
					</li>
					<li>
						<a data-toggle="collapse" href="#charts" class="collapsed" aria-expanded="false">
							<i class="ti-bar-chart-alt"></i>
							<p>Charts
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse" id="charts" role="navigation" aria-expanded="false" style="height: 0px;">
							<ul class="nav">
								<li>
									<a href="charts/chart-js-charts.html">ChartJS</a>
								</li>
								<li>
									<a href="charts/flot-charts.html">Flot</a>
								</li>
							</ul>
						</div>
					</li>
					<li>
						<a data-toggle="collapse" href="#ui-elements" class="collapsed" aria-expanded="false">
							<i class="ti-package"></i>
							<p>UI Elements
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse" id="ui-elements" role="navigation" aria-expanded="false" style="height: 0px;">
							<ul class="nav">
								<li><a href="ui/accordions.html">Accordions</a></li>
								<li><a href="ui/alerts.html">Alerts</a></li>
								<li><a href="ui/buttons.html">Buttons</a></li>
								<li><a href="ui/grid.html">Grid System</a></li>
								<li><a href="ui/icons.html">Icons</a></li>
								<li><a href="ui/modals.html">Modals</a></li>
								<li><a href="ui/notifications.html">Notifications</a></li>
								<li><a href="ui/tabs.html">Tabs</a></li>
								<li><a href="ui/typography.html">Typography</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a data-toggle="collapse" href="#forms" class="collapsed" aria-expanded="false">
							<i class="ti-clipboard"></i>
							<p>Forms
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse" id="forms" role="navigation" aria-expanded="false" style="height: 0px;">
							<ul class="nav">
								<li><a href="forms/basic-elements.html">Basic Elements</a></li>
								<li><a href="forms/advanced-elements.html">Advanced Elements</a></li>
								<li><a href="forms/form-validation.html">Form Validation</a></li>
								<li><a href="forms/form-wizard.html">Form Wizard</a></li>
								<li><a href="forms/sample-forms.html">Sample Forms</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a data-toggle="collapse" href="#tables" class="collapsed" aria-expanded="false">
							<i class="ti-view-list-alt"></i>
							<p>Tables
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse" id="tables" role="navigation" aria-expanded="false" style="height: 0px;">
							<ul class="nav">
								<li><a href="tables/tables.html">Simple Tables</a></li>
								<li><a href="tables/data-tables.html">Data Tables</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a data-toggle="collapse" href="#pages" class="collapsed" aria-expanded="false">
							<i class="ti-gift"></i>
							<p>Pages
								<b class="caret"></b>
							</p>
						</a>
						<div class="collapse" id="pages" role="navigation" aria-expanded="false" style="height: 0px;">
							<ul class="nav">
								<li><a href="sample-pages/template.html">Template</a></li>
								<li><a href="sample-pages/user-profile.html">User Profile</a></li>
								<li><a href="sample-pages/login.html">Login</a></li>
								<li><a href="sample-pages/signup.html">Sign Up</a></li>
								<li><a href="sample-pages/pricing-table.html">Pricing Table</a></li>
								<li><a href="sample-pages/invoice.html">Invoice</a></li>
								<li><a href="sample-pages/help-faqs.html">Help & FAQs</a></li>
								<li><a href="sample-pages/timeline.html">Timeline</a></li>
								<li><a href="sample-pages/404.html">404</a></li>
								<li><a href="sample-pages/500.html">500</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="calendar/calendar.html">
							<i class="ti-calendar"></i>
							<p>Calendar
								<b class="caret"></b>
							</p>
						</a>
					</li>
					<li>
						<a href="docs/documentation.html">
							<i class="ti-help"></i>
							<p>Documentation</p>
						</a>
					</li>
				</ul>

            </div>
        </div>
    @show
    @section('main-panel')
        <div class="main-panel">
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
                        <a class="navbar-brand" href="#"> Dashboard </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-bell"></i>
                                    <span class="notification">6</span>
                                    <p class="hidden-lg hidden-md">
                                        Notifications
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">You have 5 new messages</a>
                                    </li>
                                    <li>
                                        <a href="#">You're now friend with Mike</a>
                                    </li>
                                    <li>
                                        <a href="#">Wish Mary on her birthday!</a>
                                    </li>                                    <li>
                                        <a href="#">5 warnings in Server Console</a>
                                    </li>
                                    <li>
                                        <a href="#">Jane completed 'Induction Training'</a>
                                    </li>
                                    <li>
                                        <a href="#">'Prepare Marketing Report' is overdue</a>
                                    </li>
                                </ul>
                            </li>
							<li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-layout-grid3-alt"></i>
                                    <p class="hidden-lg hidden-md">Apps</p>
                                </a>
                            </li>
                            <li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-user"></i>
                                    <p class="hidden-lg hidden-md">Profile</p>
                                </a>
                            </li>
							<li>
                                <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-settings"></i>
                                    <p class="hidden-lg hidden-md">Settings</p>
                                </a>
                            </li>
                            <li class="separator hidden-lg hidden-md"></li>
                        </ul>
                    </div>
                </div>
            </nav>
    @show
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div> 
            </div>
        </div>
    </div>


    <!--script src="https://code.jquery.com/jquery-1.10.2.min.js')}}"</script>
    

    <script src=" {{ asset('js/main.js')                    }} "></script-->

    <script src=" {{asset('js/jquery-3.1.1.min.js')}} " type="text/javascript"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/bootstrap.min.js')}}" type="text/javascript"></script>
    <!--script src="{{asset('js/material.min.js')}}" type="text/javascript"></script-->
    <!--script src="{{asset('js/perfect-scrollbar.jquery.min.js')}}" type="text/javascript"></script-->
    <!-- Forms Validations Plugin -->
    <script src="{{asset('js/jquery.validate.min.js')}}" type="text/javascript"></script>
    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->
    <script src="{{asset('js/moment.min.js')}}" type="text/javascript"></script>
    <!--  Charts Plugin -->
    <script src="{{asset('js/charts/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('js/charts/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('js/charts/flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('js/charts/flot/jquery.flot.stack.js')}}"></script>
    <script src="{{asset('js/charts/flot/jquery.flot.categories.js')}}"></script>
    <script src="{{asset('js/charts/chartjs/Chart.min.js')}}" type="text/javascript"></script>
    
    <!--  Plugin for the Wizard -->
    <script src="{{asset('js/jquery.bootstrap-wizard.js')}}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{asset('js/bootstrap-notify.js')}}"></script>
    <!-- DateTimePicker Plugin -->
    <script src="{{asset('js/bootstrap-datetimepicker.js')}}"></script>
    <!-- Vector Map plugin -->
    <script src="{{asset('js/jquery-jvectormap.js')}}"></script>
    <!-- Sliders Plugin -->
    <script src="{{asset('js/nouislider.min.js')}}"></script>
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ81-fUpHTJ73LOtZLzZjGjkUWl0TtvWA&libraries=places"></script>
    <script src=" {{ asset('jquery/locationpicker.jquery.js')  }} "></script>
    <!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAurmSUEQDwY86-wOG3kCp855tCI8lHL-I"></script-->
    <!-- Select Plugin -->
    <script src="{{asset('js/jquery.select-bootstrap.js')}}"></script>
    
    <!--  Checkbox, Radio, Switch and Tags Input Plugins -->
    <script src="{{asset('js/bootstrap-checkbox-radio-switch-tags.js')}}"</script>
    
    <!-- Circle Percentage-chart -->
    <script src="{{asset('js/jquery.easypiechart.min.js')}}"</script>
    
    <!--  DataTables.net Plugin    -->
    <script src="{{asset('js/jquery.datatables.js')}}"</script>
    <!-- Sweet Alert 2 plugin -->
    <script src="{{asset('js/sweetalert2.min.js')}}"</script>
    <!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{asset('js/jasny-bootstrap.min.js')}}"</script>
    <!--  Full Calendar Plugin    -->
    <script src="{{asset('js/fullcalendar.min.js')}}"</script>
    <!-- TagsInput Plugin -->
    <script src="{{asset('js/jquery.tagsinput.js')}}"</script>
    <!-- Material Dashboard javascript methods -->
    <script src="{{asset('js/amaze.js')}}"</script>

    <script src="{{asset('js/charts/flot-charts.js')}}"</script>
    <script src="{{asset('js/charts/chartjs-charts.js')}}"</script>

    <script src=" {{ asset('jquery/jquery-ui-1.9.2.custom.js')  }} "></script>
    <script src=" {{ asset('jquery/jquery.timepicker.js')       }} "></script>
    
    <script type="text/javascript">
    	//$(document).ready(function(){
    	//	//demo.initStatsDashboard();
    	//	demo.initVectorMap();
    	//	demo.initCirclePercentage();
    
    	//});
    </script>
</body>
</html>

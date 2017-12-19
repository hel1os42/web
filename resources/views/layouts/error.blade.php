@include('partials.head')
<body>
    <div class="error_page_header">
        <div class="m-l-25 uk-container-center">
            @yield('error-num')
        </div>
    </div>
    <div class="error_page_content">
        <div class="m-l-25">
            @yield('error-msg')
            <a href="#" onclick="history.go(-1);return false;">Go back to previous page</a>
        </div>
    </div>
</body>
</html>

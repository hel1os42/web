@if(false)
    <!--
    Используем:
    jquery-3.1.1
    bootstrap
    script.js - пользовательские скрипты для всего сайта
-->
@endif

@push('scripts')
    <script>
        window.nau_lang = {!! json_encode(__('js_messages')) !!};
    </script>
@endpush

<script src="{{ asset('js/jquery-3.1.1.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/dateformat.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/script.js') }}"></script>

<script src="{{ asset('js/jasny-bootstrap.min.js') }}"></script>

@if(false)
    <script src="{{ asset('js/amaze.js') }}"></script>
    <!--
    подключать только на нужных страницах:
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ81-fUpHTJ73LOtZLzZjGjkUWl0TtvWA&libraries=places"></script>
    <script src="{{ asset('jquery/locationpicker.jquery.js')  }}"></script>
    -->
@endif
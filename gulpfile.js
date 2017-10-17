const elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.copy('resources/assets/css/', 'public/css/');
});

elixir(function(mix) {
    mix.copy('resources/assets/js/', 'public/js/');
});

elixir(function(mix) {
    mix.copy('resources/assets/fonts/', 'public/fonts/');
});

elixir(function(mix) {
    mix.copy('resources/assets/img/', 'public/img/');
});

// Import Elixir.
var elixir = require('laravel-elixir');

// Configure Elixir.
config.publicPath = 'web/assets';
config.appPath = 'src';
config.assetsPath = 'app/Resources/assets';

// Set up Elixir tasks.
elixir(function(mix) {
    // Elixir Tasks Here
    mix.styles(['app.css']);
});
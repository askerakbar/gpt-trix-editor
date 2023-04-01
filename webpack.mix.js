const mix = require('laravel-mix');
mix.setPublicPath('dist');
mix.js( 'resources/js/gpt-trix-editor.js','dist')

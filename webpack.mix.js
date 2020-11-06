let mix = require('laravel-mix');
const fs = require('fs');
require('mix-env-file');
mix.env(process.env.ENV_FILE);
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copyDirectory('resources/fonts', 'public/fonts');

function scanDir(mix, path, cssPath, jsPath) {
    if (fs.existsSync(path) && fs.lstatSync(path).isDirectory()) {
        fs.readdirSync(path).forEach(file => {
            if (file.indexOf('.scss') !== -1) {
                mix.sass(path + file, cssPath);
            }
            else if (file.indexOf('.css') !== -1) {
                mix.copy(path + file, cssPath);
            }
            else if (file.indexOf('.js') !== -1) {
                mix.js(path + file, jsPath);
            }
            else if (fs.lstatSync(path + file).isDirectory()) {
                scanDir(mix, path + file + '/', cssPath + file + '/', jsPath + file + '/');
            }
        });
    }
    return mix;
}

var scss_files = fs.readdirSync('resources/views');
scss_files.forEach(dir => {
    var path = 'resources/views/' + dir + '/';
    var cssPath = 'public/css/views/' + dir + '/';
    var jsPath = 'public/js/views/' + dir + '/';
    mix = scanDir(mix, path, cssPath, jsPath);
});

if (mix.inProduction()) {
    mix.version();
}

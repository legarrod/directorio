/* import necessary npm packages */
var gulp = require('gulp'),
    rtlcss = require('gulp-rtlcss'),
    sass = require('gulp-sass'),
    uglify = require('gulp-uglify'),
    cleancss = require('gulp-clean-css'),
    concat = require('gulp-concat'),
    sourcemaps = require('gulp-sourcemaps'),
    browserSync = require('browser-sync').create(),
    autoPrefixer = require('gulp-autoprefixer'),
    gulpInject = require('gulp-inject'),
    series = require("stream-series"),
    merge = require('merge-stream'),
    rename = require('gulp-rename'),
    tidyHtml = require('gulp-remove-empty-lines'),
    formatHtml = require('gulp-html-beautify'),
    gulpfilter = require('gulp-filter'),
    tinypngs = require('gulp-tinypng-compress'),
    projectName = require('./package.json').name;

// Assets sources
var vendor = './src/vendor_assets',
    theme = './src/theme_assets',
    vendorAssets = gulp.src(
        [
            vendor + '/css/bootstrap/*.css',
            vendor + '/css/*.css',
            vendor + '/js/jquery/*.js',
            vendor + '/js/bootstrap/popper.js',
            vendor + '/js/bootstrap/bootstrap.min.js',
            vendor + '/js/*.js'
        ], {read: true}),

    themeAssets = gulp.src(
        [
            'src/style.css',
            theme + '/js/*.js'
        ], {read: true});


/* scss to css compilation */
function sassCompiler(src, dest) {
    return function () {
        gulp.src(src)
            .pipe(sourcemaps.init())
            .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
            .pipe(autoPrefixer('last 10 versions'))
            .pipe(sourcemaps.write('maps'))
            .pipe(gulp.dest(dest))
            .pipe(browserSync.reload({
                stream: true
            }));
    }
}

// bootstrap sass compiler
gulp.task('sass:bs', sassCompiler('./vendor_assets/css/bootstrap/bootstrap.scss', './vendor_assets/css/bootstrap/'));

// themes sass compiler
gulp.task('sass:theme', sassCompiler('./theme_assets/sass/style.scss', './'));

/* gulp asset injection */
gulp.task('inject', function () {
    gulp.src('./src/*.html')
        .pipe(gulpInject(series(vendorAssets, themeAssets), { relative: true }))
        .pipe(gulp.dest('./src/'))
});

/* gulp serve content browser */
gulp.task('serve', function () {
    browserSync.init({
        server: {
            baseDir: './src'
        },
        port: 3010
    })
});


// image optimization task
gulp.task('imgoptimize', function () {
    var svgFilter = gulpfilter(['**/*.svg'], {restore: true});
    gulp.src('./src/img/**')
        .pipe(svgFilter)
        .pipe(cleancss())
        .pipe(gulp.dest('dist/img/svg'))
        .pipe(svgFilter.restore)
        .pipe(
            tinypngs({
                key: 'gu3SUgQf1WyxcB3_xxRmIEMdt7zWZeh_', // TO KNOW MORE SEE THE DOCUMENTATION
                sigFile: 'src/images/.tinypng-sigs',
                log: true
            })
        )
        .pipe(gulp.dest('./dist/img'));
});

// default gulp task
gulp.task('default', ['sass:theme', 'inject'], function () {
    gulp.watch('./theme_assets/sass/**/*', ['sass:theme']);
    gulp.watch('./vendor_assets/css/bootstrap/*.scss', ['sass:bs']);
    // gulp.watch('./src/**/*.js', browserSync.reload);
});


/* CFBS ejection script beta */
var filesToMove = [
    vendor + '/**',
    theme + '/**',
    './src/img/**/*.*'
];

// move files
gulp.task('move:files', function () {
    gulp.src(filesToMove, {base: './src'})
        .pipe(gulp.dest(projectName+'/src'));
});

//compile for tf
gulp.task('compileStyleForTf', sassCompiler('./src/theme_assets/sass/style.scss', projectName+'/src'));

// eject themeforrest version
gulp.task("eject:tf", ['move:files', 'compileStyleForTf'], function () {
    gulp.src('./src/*.html')
        .pipe(tidyHtml())
        .pipe(formatHtml(
            {
                indentSize: 4
            }
        ))
        .pipe(gulp.dest(projectName+'/src'));

    gulp.src('./build-config/**')
        .pipe(gulp.dest('./'+projectName));
});

// eject optimized  version for demo
gulp.task('distAssets', function () {
    var jsFilter = gulpfilter(['**/*.js'], {restore: true}),
        cssFilter = gulpfilter(['**/*css'], {restore: true}),
        thmis = gulpfilter(['**/*.js'], {restore: true});

    var va = vendorAssets
        .pipe(jsFilter)
        .pipe(uglify())
        .on('error', function (e) {
            console.log(e);
        })
        .pipe(concat('plugins.min.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(jsFilter.restore)
        .pipe(cssFilter)
        .pipe(cleancss(
            {
                compatibility: 'ie8',
                rebase: false
            }))
        .pipe(concat('plugin.min.css'))
        .pipe(gulp.dest('./dist/css'));

    var ta = themeAssets
        .pipe(thmis)
        .pipe(uglify())
        .on('error', function (e) {
            console.log(e);
        })
        .pipe(concat('script.min.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(thmis.restore)
        .pipe(gulpfilter(['**/*.css']))
        .pipe(cleancss({compatibility: 'ie8'}))
        .pipe(concat('style.css'))
        .pipe(gulp.dest('./dist'));

    var fonts = gulp.src('./src/vendor_assets/fonts/**')
        .pipe(gulp.dest('dist/fonts'));

    var moveHtml = gulp.src('src/*.html')
        .pipe(gulp.dest('dist'));

    return merge(va, ta, fonts, moveHtml);
});

// eject demo
gulp.task('eject:demo', ['distAssets'], function () {
    gulp.src('dist/*.html')
        .pipe(gulpInject(
            gulp.src(['dist/css/*.css', 'dist/js/*.js', 'dist/*.css']),
            {relative: true}
        ))
        .pipe(gulp.dest('dist'));
});

//rtl css generator
gulp.task('rtl', function () {
    var bootstrap = gulpfilter('**/bootstrap.css', {restore: true}),
        style = gulpfilter('**/style.css', {restore: true});

    gulp.src(['vendor_assets/css/bootstrap/bootstrap.css', 'style.css'])
        .pipe(rtlcss({
            'stringMap': [
                {
                    'name': 'left-right',
                    'priority': 100,
                    'search': ['left', 'Left', 'LEFT'],
                    'replace': ['right', 'Right', 'RIGHT'],
                    'options': {
                        'scope': '*',
                        'ignoreCase': false
                    }
                },
                {
                    'name': 'ltr-rtl',
                    'priority': 100,
                    'search': ['ltr', 'Ltr', 'LTR'],
                    'replace': ['rtl', 'Rtl', 'RTL'],
                    'options': {
                        'scope': '*',
                        'ignoreCase': false
                    }
                }
            ]
        }))
        .pipe(bootstrap)
        .pipe(rename({suffix: '-rtl', extname: '.css'}))
        .pipe(gulp.dest('vendor_assets/css/bootstrap/'))
        .pipe(bootstrap.restore)
        .pipe(style)
        .pipe(rename({suffix: '-rtl', extname: '.css'}))
        .pipe(gulp.dest(''));
});

var gulp       = require( 'gulp' ),
    sass       = require( 'gulp-sass' ),
    sass_glob  = require( 'gulp-sass-glob' ),
    sourcemaps = require( 'gulp-sourcemaps' ),
    concat     = require( 'gulp-concat' );

gulp.task( 'sass:watch',
    function () {
        gulp.watch( 'assets/scss/**/*', gulp.series( 'sass' ) )
    } );

gulp.task( 'sass:watch-reports',
    function () {
        gulp.watch( 'assets/scss/**/*', gulp.series( 'sass:reports' ) )
    } );

gulp.task( 'sass',
    function () {
        return gulp.src( 'assets/scss/v2/theme.scss' )
            .pipe( sass_glob() )
            .pipe( sourcemaps.init() )
            .pipe( sass().on( 'error', sass.logError ) )
            .pipe( gulp.dest( 'assets/css' ) );
    } );

gulp.task( 'sass:reports',
    function () {
        return gulp.src( 'assets/scss/v2/theme-reporting.scss' )
            .pipe( sass_glob() )
            .pipe( sourcemaps.init() )
            .pipe( sass().on( 'error', sass.logError ) )
            .pipe( gulp.dest( 'assets/css' ) );
    } );

gulp.task( 'sass:admin',
    function () {
        return gulp.src( 'assets/scss/theme-admin.scss' )
            .pipe( sass_glob() )
            .pipe( sourcemaps.init() )
            .pipe( sass().on( 'error', sass.logError ) )
            .pipe( gulp.dest( 'assets/css' ) );
    } );

gulp.task( 'sass:editor',
    function () {
        return gulp.src( 'assets/scss/theme-editor.scss' )
            .pipe( sass_glob() )
            .pipe( sourcemaps.init() )
            .pipe( sass().on( 'error', sass.logError ) )
            .pipe( gulp.dest( 'assets/css' ) );
    } );

gulp.task( 'js:bundle',
    function () {
        return gulp.src( 'assets/js/site/**/*.js' )
            .pipe( sourcemaps.init() )
            .pipe( concat( 'site.js' ) )
            .pipe( sourcemaps.write() )
            .pipe( gulp.dest( 'assets/js' ) );
    } );


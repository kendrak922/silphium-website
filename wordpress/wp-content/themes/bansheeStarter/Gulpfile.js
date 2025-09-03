"use strict";

/****************************************
    DEPENDENCIES
*****************************************/
/*
 * This list of dependency variables comes from the package.json file. Ensure any dependency listed here is also added to package.json.
 * These variables are declared here at the top and are used throughout the gulpfile to complete certain tasks and add functionality.
 */
const fs = require('fs');
const autoprefixer = require('autoprefixer');
const browsersync = require('browser-sync').create();
const concat = require('gulp-concat');
const cssnano = require('cssnano');
const gulp = require('gulp');
// const imagemin = require('gulp-imagemin');
const newer = require("gulp-newer");
const notify = require("gulp-notify");
const plumber = require("gulp-plumber");
const postcss = require("gulp-postcss");
const rename = require('gulp-rename');
const sass =  require('gulp-sass')(require('sass'));
// sass.compiler = require('sass');
const shell = require('gulp-shell');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
// Docs
const sassdoc = require('sassdoc');
const jsdoc = require('gulp-jsdoc3');
const exec = require('child_process').exec;
const template = require('gulp-template');
const webserver = require('gulp-webserver');
// const filesystem = require('file-system');


/****************************************
    SOURCE PATHS
*****************************************/
/**
 * The 'config' object defines where all the assets are found.
 * Changing the values of this object will change where all the tasks below look for files
 */

// Common defaults
const path_src = './assets/src/';
const path_dist = './assets/dist/';

// Pathing config
const config = {
    theme: {
        name: 'theme', // if you change this value, update your file enqueue's too. This is a prefix for all file names (usage example: config.theme.name)
    },
    css: {
        sass: path_src + 'sass/style.scss',
        sass_comps: path_src + 'sass/**/*.scss',
        sass_blocks: './template-parts/blocks/_assets/blocks-imports.scss',
        sass_blocks_comps: './template-parts/blocks/**/*.scss',
        vendor_src: path_src + 'vendor/css/**/*.css',
        dist: path_dist + 'css/',
    },
    js: {
        src: [
            path_src + 'js/**/*.js', // Wildcard - Used as a catch-all. This will add all .js files located within assets/src/js/ to be compiled.
            // path_src + 'js/main.js', // Manual - FOR DEPENDENCIES - if you want to control your enqueue order, manually add each file in the order you'd like
        ],
        src_blocks: [
            './template-parts/blocks/**/*.js', // Wildcard - Used as a catch-all. This will add all .js files located within assets/src/js/ to be compiled.
            // './template-parts/blocks/custom-content/custom-content.js', // Manual - FOR DEPENDENCIES - if you want to control your enqueue order, manually add each file in the order you'd like
        ],
        src_vendor: [
            path_src + 'vendor/js/**/*.js', // Wildcard - used as a catch-all. This will add all .js files located within assets/src/vendor/js/ to be compiled and minfied.
            // path_src + 'vendor/js/slick.js', // Manual - FOR DEPENDENCIES - if you want to control your enqueue order, manually add each file in the order you'd like
        ],
        dist: path_dist + 'js/',
    },
    imgs: {
        src: [
            path_src + 'imgs/*',
            path_src + 'imgs/**/*',
        ],
        dist: path_dist + 'imgs/',
    },
    docs: {
        index: './assets/src/docs/index.html',
        json: './assets/src/docs/jsdoc.json',
        serve: './docs/',
    }
};



/****************************************
    STANDARD TASKS
*****************************************/

/**
 * COMPILE GLOBAL SASS :: UN-MINIFIED & MINIFIED
 */
async function styles() {
    // Define plugins for "PostCSS"
    var plugins_expanded = [
        autoprefixer()
    ];
    var plugins_min = [
        cssnano()
    ];

    // Run SASS Task
    return gulp.src( config.css.sass )
        .pipe(plumber({ 
            errorHandler: () => {
                notify.onError("SASS Global Error: <%= error.message %>");
                process.nextTick(() => process.exit(1));
            } // on error, send push and exit
        }))
        .pipe(sourcemaps.init()) // Begin SCSS mapping
        .pipe(sass({
            outputStyle: 'expanded'
        }))
        .pipe(postcss( plugins_expanded ))
        .pipe(sourcemaps.write()) // Write SCSS maps
        .pipe(rename(config.theme.name + '-custom.css'))
        .pipe(gulp.dest( config.css.dist )) // DIST un-minified file

        // minify for production
        .pipe(rename(config.theme.name + '-custom.min.css')) // rename with .min
        .pipe(postcss( plugins_min )) // minify
        .pipe(gulp.dest( config.css.dist )); // DIST minified version
        
}


/**
 * COMPILE & MINIFY VENDOR & MISC CSS
 */
async function styles_vendor() {
    return gulp.src( config.css.vendor_src )
        .pipe(plumber({ 
            errorHandler: () => {
                notify.onError("Vendor CSS Error: <%= error.message %>");
                process.nextTick(() => process.exit(1));
            } // on error, send push and exit
        }))
        .pipe(concat(config.theme.name + '-vendor.min.css')) // group files together
        .pipe(postcss([ cssnano() ])) // minify
        .pipe(gulp.dest( config.css.dist )); // DIST minified version
}


/**
 * COMPILE CUSTOM JS :: UN-MINIFIED & MINIFIED
 */
async function scripts_global() {
	return gulp.src( config.js.src )
        .pipe(plumber({ 
            errorHandler: () => {
                notify.onError("JS Global Error: <%= error.message %>");
                process.nextTick(() => process.exit(1));
            } // on error, send push and exit
        }))
        .pipe(concat(config.theme.name + '-custom.js')) // group files together
	    .pipe(gulp.dest( config.js.dist )) // DIST un-minified file

	    // minify for production
	    .pipe(rename(config.theme.name + '-custom.min.js')) // rename with .min
	    .pipe(uglify()) // minify
	    .pipe(gulp.dest( config.js.dist )); // DIST minified version
};


/**
 * COMPILE CUSTOM BLOCKS JS :: UN-MINIFIED & MINIFIED
 */
async function scripts_blocks() {
    return gulp.src( config.js.src_blocks )
        .pipe(plumber({ 
            errorHandler: () => {
                notify.onError("JS Blocks Error: <%= error.message %>");
                process.nextTick(() => process.exit(1));
            } // on error, send push and exit
        }))
        .pipe(concat(config.theme.name + '-custom-blocks.js')) // group files together
        .pipe(gulp.dest( config.js.dist )) // DIST un-minified file

        // minify for production
        .pipe(rename(config.theme.name + '-custom-blocks.min.js')) // rename with .min
        .pipe(uglify()) // minify
        .pipe(gulp.dest( config.js.dist )); // DIST minified version
};


/**
 * COMPILE & MINIFY VENDOR JS
 */
async function scripts_vendor() {
    return gulp.src( config.js.src_vendor )
        .pipe(plumber({ 
            errorHandler: () => {
                notify.onError("Vendor JS Error: <%= error.message %>");
                process.nextTick(() => process.exit(1));
            } // on error, send push and exit
        }))
        .pipe(concat(config.theme.name + '-vendor.js')) // group files together
        .pipe(gulp.dest( config.js.dist )) // DIST un-minified file

        // minify for production
        .pipe(rename(config.theme.name + '-vendor.min.js')) // rename with .min
        .pipe(uglify()) // minify
        .pipe(gulp.dest( config.js.dist )); // DIST minified version
}


// /**
//  * OPTIMIZE IMAGES & DIST TO THEME
//  */
// async function images() {
//     return gulp.src( config.imgs.src )
//         .pipe(plumber({ 
//             errorHandler: () => {
//                 notify.onError("Images Error: <%= error.message %>");
//                 process.nextTick(() => process.exit(1));
//             } // on error, send push and exit
//         }))
//         .pipe(newer( config.imgs.dist )) // check DIST for existing assets
//         .pipe(
//             imagemin([ // optimize images per image type
//                 imagemin.gifsicle({ interlaced: true }),
//                 imagemin.jpegtran({ progressive: true }),
//                 imagemin.optipng({ optimizationLevel: 5 }),
//                 imagemin.svgo({
//                     plugins: [{
//                         removeViewBox: false,
//                         collapseGroups: true
//                     }]
//                 })
//             ])
//         )
//         .pipe(gulp.dest( config.imgs.dist )); // DIST optimized versions
// }




/****************************************
    DEFINED TASKS
*****************************************/

/**
 * BROWSER SYNC
 *
 * https://browsersync.io/docs/gulp
 * This will not reload the browser on every change
 * It will just output an IP that is available to any device on the network.
 * Meant for Testing PC and Devices.
 */
function browser_sync(done) {
    browsersync.init({
        proxy: 'http://wp.test:8888', // replace yoursitename with the url of your local site.
        open: false,
    });
    done();
}


/**
 * COMMAND LINE
 *
 * Define a command to run (you may need to 'cd' into the correct directory first)
 */
let shell_cmd = "echo sample command_line command;";
shell_cmd += "echo sample 2nd command;"; // Can be a series of commands seperated by ';'

// Run command_line var
gulp.task('command_line',
    shell.task(shell_cmd, {
        shell: 'bash',
    })
);



/****************************************
    CUSTOM PROJECT TASKS
*****************************************/
// Define custom tasks for your specific project




/****************************************
        Doc Generation Tasks
*****************************************/
//Sass documentation generator
function docs_sass(){
    var options = {
        dest: config.docs.serve + 'sass',
    };
    return gulp.src(config.css.sass_comps).pipe(sassdoc(options));
}

//JS documentation generator
function docs_js(cb) {
    var conf = require(config.docs.json);
    gulp.src(config.js.src, {read: false})
        .pipe(jsdoc(conf,cb));
    cb();
}
function docs_js_blocks(cb) {
    var conf = require(config.docs.json);
    gulp.src(config.js.src_blocks, {read: false})
        .pipe(jsdoc(conf,cb));
    cb();
}
// PHP documentation generator
function docs_php(cb){
    exec('php phpDocumentor.phar -d . -t '+config.docs.serve+'php ', function (err, stdout, stderr) {
        console.log(err)
        console.log(stdout)
        console.log(stderr)
    });
    cb();
}
// generate landing page for viewing docs
//create name for landing page based off the name of the theme
var name = __dirname.split("/");
// var fileContent = filesystem.readFileSync("./README.md", "utf8");
name = name[name.length - 1];
function docs_landing(cb){
    gulp.src(config.docs.index)
        .pipe(template(
            {
                name: name,
                readMe: fileContent,
                jsLink: config.docs.serve + 'js/index.html',
                sassLink: config.docs.serve + 'sass/index.html',
                phpLink: config.docs.serve + 'php/index.html'
        }
        ))
        .pipe(gulp.dest(config.docs.serve));
    cb();
}
// serve site of documentation opens on localhost 8000
function docs_serve() {
    gulp.src(config.docs.serve)
    .pipe(webserver({
        livereload: true,
        fallback: 'index.html',
        open: true,
    }));
}



/***** DOC update and serve TASKS *****/
// Only generates docs, doesn't open server run gulp docserve to serve docs, use when docs are already served
const updateDocs = gulp.series([ docs_sass, docs_js, docs_php ]);
// Creates documentation in Docs folder for JS, SASS, and PHP, and then opens webserver to view docs
const document = gulp.series([ docs_sass, docs_js, docs_php, docs_landing, docs_serve ]);








/****************************************
    ACTIONS
*****************************************/

// BUILD TASK - COMPILES SCSS, CSS & JS, but does NOT watch for file changes
const build = gulp.series([ styles_vendor, styles, scripts_vendor, scripts_global, scripts_blocks ]);


// WATCH AND LOG SOURCE FILE CHANGES
async function watch(){
    // WATCH SASS / CSS
    gulp.watch( config.css.sass, gulp.series([ styles ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });
    gulp.watch( config.css.sass_comps, gulp.series([ styles ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });
    gulp.watch( config.css.sass_blocks, gulp.series([ styles ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });
    gulp.watch( config.css.sass_blocks_comps, gulp.series([ styles ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });
    gulp.watch( config.css.vendor_src, gulp.series([ styles_vendor ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });

    // WATCH JS
    gulp.watch( config.js.src, gulp.series([ scripts_global ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });
    gulp.watch( config.js.src_blocks, gulp.series([ scripts_blocks ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });
    gulp.watch( config.js.src_vendor, gulp.series([ scripts_vendor ]))
        .on('change',(event) => {
            console.log('File ' + event + ' was updated, running tasks...');
        });

    // WATCH IMAGES
    // gulp.watch( config.imgs.src, gulp.series([ images ]))
    //     .on('change',(event) => {
    //         console.log('File ' + event + ' was updated, running tasks...');
    //     });
}


// DEFAULT GULP TASK
const start = gulp.series([ build, watch ]);


/****************************************
    EXPORTS
*****************************************/
// Dev
exports.styles = styles;
exports.styles_vendor = styles_vendor;
exports.scripts_global = scripts_global;
exports.scripts_blocks = scripts_blocks;
exports.scripts_vendor = scripts_vendor;
// exports.images = images;
exports.browser_sync = browser_sync;
exports.build = build;
exports.watch = watch;
exports.default = start;

// Docs
exports.docs_sass = docs_sass;
exports.docs_js = docs_js;
exports.docs_js_blocks = docs_js_blocks;
exports.docs_php = docs_php;
exports.docs_landing = docs_landing;
exports.docs_serve = docs_serve;
exports.updateDocs = updateDocs;
exports.document = document;




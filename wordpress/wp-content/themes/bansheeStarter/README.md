# WORDPRESS STARTER THEME - 

This theme is the result of all of the mentors, youtube videos, and paired programs I have worked with through the years. 
---

## Overview of Directories and Files

### Directories

- **dist/** - compiled assets (these get rewritten by Gulp)
- **fonts/** - a place to put locally hosted fonts. These fonts are included in SASS by "src/sass/global/fonts.scss"
- **node_modules/** - these are the node.js dependencies for this theme including gulp and gulp-sass, this directory is not part of the repo but is created when you setup or clone this project for the first time (by running npm install from the theme directory)
- **src/** - the source files that create the compiled assets, includes sass, bourbon and neat, js
- **template-parts/** - put partials that get called by wordpress templates here
- **template-parts/blocks/** - block directories holding PHP render templates and scss/js assets for each block
- **vendor/** - these are the composer.json dependencies for this theme including moxie-lean/loader, this directory is not part of the repo but is created when you setup or clone this project for the first time (by running composer install from the theme directory)

### Files

- **.gitignore** - this is set up to work with this theme, can be edited to meet your needs
- **composer.json** - this is what tells composer what packages to require when you run "composer install"
- **functions-library.php** - these are snippets for common WordPress functions that you can move into functions.php if you need them
- **gulpfile.js** - this is what tells Gulp what to do, it can be edited if need be
- **package.json** - this is what tells node package manager what to do when you run "npm install"
- **README.md** - this is where you should put documentation for the theme you create
- **style.css** - this is required by WordPress in order to register as a valid theme, don't use for any CSS. All CSS should be added within the "src/sass" files and be compiled by Gulp.

&nbsp;

---

### Enqueueing Scripts and Stylesheets

If you need to add another stylesheet or script, add it to the `theme_enqueue_assets()` function in functions.php and don't put it in the header. This is the recommended way of adding scripts and stylesheets to WordPress to avoid conflicts between themes and plugins and it also prevents dependencies from being loaded multiple times. The `theme_enqueue_assets()` function is set up to preload jQuery as a dependency of main.js so you don't need to add it anywhere else.

&nbsp;

---

## Gutenberg Blocks

## About Blocks

- This site is built using Gutenberg Blocks + ACF.
  - This is not using the native React blocks, instead PHP based using ACF
- The native core blocks have been removed from the page builders to reduce content entry complexity. Only ACF blocks are allowed
- Block files and registrations can be found within `./template-parts/blocks/`
- There is a NPM script within package.json that is used to push block registration files to staging/production from local. More details below

### Registering Blocks

1. Create block files locally
2. Create new register file within template-parts/blocks/\_\_register/ (you can duplicate from an existing and rename). For example: register-test.php
3. Add reference to block's sass file to template-parts/blocks/\_assets/blocks-imports.scss
4. Go onto staging, create your new field group and map it to the newly registered block
5. Go to your dev page, add the block and content
6. Pull the database from staging to local
7. Develop the section locally and commit changes to the repo
8. Done!

&nbsp;

---

## Static Assets: CSS & JS

### Gulp

- This theme uses gulp to watch and compile source files into distribution files for the browser.

### CSS

This theme does not use any standard CSS, please do not write styles within the default styles.css. All CSS should be added via SASS within the `assets/src/sass/` directory. This directory is compiled by Gulp and distributed to the `assets/dist/css/` directory for use in production.

#### SASS Compiling

The stylesheets are set up to be compiled with SASS. Our theme includes a reset file called `normalize.scss`. There are a few basic sass variables and mixins found in `assets/src/sass/utility/variables.scss` and `assets/src/sass/utility/mixins.scss` which you can use/modify/delete but they are there as a start.

There's a Gulp file setup to compile sass files. In terminal, if you open the theme directory and run `gulp` it will compile all source Sass styles to `assets/dist/css/style.css`. You may need to run `npm install` the first time you use the theme to install Gulp and other node dependencies locally in your project.

#### SASS Partials

This theme uses the most up to date method of using partials in Sass, [Sass Modules](https://sass-lang.com/blog/the-module-system-is-launched). You will find Sass members included with [@use](https://sass-lang.com/documentation/at-rules/use) and [@forward](https://sass-lang.com/documentation/at-rules/forward) rules instead of@use rules. When creating and using new Sass members, Be aware that the @use and @forward at-rules don't make members globally available the same way@use does.

#### SASS Variables

The SASS variables are defined within `assets/src/sass/utility/variables.scss`. You are welcome to add more variables to help make your theme more globally controlled. If you find yourself writing the same value multiple times over, you should define a SASS variable or mixin for it. Consider the [DRY method.](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself) Variables and Mixins are very helpful when it comes to making consistent updates throughout the site's lifecycle.

#### SASS Mixins

You will find the majority of mixins within the theme in `assets/src/sass/utility/mixins.scss` and `assets/src/sass/utility/fonts.scss`. The font mixin set is designed to help make your fonts more centralized throughout the theme. The mixins are especially helpful when it comes to a design change later in the theme's lifecycle.

&nbsp;

### JavaScript

- All of the global JS should be written within the `assets/src/js/` directory.
- There are also JS constructors found within `template-parts/blocks/block-name/block-name.js` for each block as needed.
- Both sets of JS are compiled by Gulp and distributed to the `assets/dist/js/` directory for use in production by two separate tasks.

#### A note about jQuery "No-Conflict Mode"

Because WordPress loads jquery in no-conflict mode, the `$` alias will work only inside a document ready function with this syntax:

```js
jQuery(document).ready(function ($) {
  // Do things with $
})
```

In order to use the `$` alias outside of the document ready function, wrap it in this function instead:

```js
;(function ($) {
  // Do things with $
})(jQuery)
```

source: from a post by Chris Coyier [Using jQuery in WordPress](https://digwp.com/2011/09/using-instead-of-jquery-in-wordpress/).

**ALTERNATIVELY...**
You can deregister the pre-registered version of jQuery and add your own:

```php
// To deregister the existing jquery:
wp_deregister_script('jquery');

// To enqueue your own:
$scripts_jquery = '/dist/js/<name of your jquery script goes here>';
wp_register_script(
  'jquery',
  get_stylesheet_directory_uri() . $scripts_jquery,
  null,
  filemtime(get_stylesheet_directory() . $scripts_jquery),
  true
);
wp_enqueue_script('jquery');
```

Then you can use the regular document ready function and `$` alias as you normally would.

&nbsp;

---

## Accessibility (ADA/WCAG)

### Skip Links

Skip links are key to making a website accessible. They allow a user using a screen reader to skip over hearing the entire header and nav read and skip to the main content. Its href is an anchor link that goes directly to the #main div. The link is in the header, the #main div it links to is in page.php, and the style that hides the link (position:absolute;top-50px;) is in global.scss.

---

## Atomic Design (Lean Loader)

If you want to use a component, include this line near top of your file:
use Lean\Load;

Using the 'loader_alias' and 'loader_directories' filters in functions.php, we can tell wordpress to look in the right folder when we make our commands. The various aliases you can use are:
Load::atom
Load::molecule
Load::organism

You can then inclue an atom/molecule/organism using them. The first argument is the component you're loading, and the second is an array of data to pass to it. For example:
Load::atom(
'button/button',
[
'button' => \$button['button'],
]
);

Happy atomic designing!

---

## VSCode Shortcuts

Option+arrow: moves a line
Shift+Option+arrow: copies a line
command+p: find a file
command+shift+f: Global find

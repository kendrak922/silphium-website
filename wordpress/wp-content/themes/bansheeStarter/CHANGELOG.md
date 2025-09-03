# Changelog

All notable changes to this project will be documented in this file.

## [1.2.0] - 2022-12-22

### Changed
- Switched from Gulp Sass to Dart Sass
- Updated to Sass Modules System:
- -> Switched from@use at-rules to @use and @forward at-rules
- -> Created index.scss files in /components, /utility and /layout allowing entire directory to be @used or @forwarded with one line
- -> For now, everything is namespaced as a wildcard so that existing variables and mixins didn't need to be prefixed with a namespace

____________________________________________________________


## 2021-11-29

### Changed
- Moved repo from CodeCommit to GitHub. No File changes, therefore no version change. But worth noting.

## [1.1.0] - 2021-07-26

### Added
- Added Core Version & Plugin Testing as a section so that whenever there is an update to the core of a common plugin it is tested for errors (README.md)
- Added to the CHANGELOG.md file for major changes/updates to the theme

### Fixed
- Updated depreciated block categories and allowed block types functions based the the WordPress core 5.8 update (/functions.php)
- Hero Banner block: Slider not initializing due to layout error (/template-parts/blocks/hero-banner/)
- Full Width Media block: Video display for both local and embed (/template-parts/blocks/full-width-media/)
- Custom Content block: Video display for both local and embed (/template-parts/blocks/custom-content/)

### Changed
- Modified ACF Options page to be “Theme Options” and have it higher on the main menu (/functions.php)

### Removed
- 'Unregistered Block Patterns' function (/functions.php)
- 'includes/social-media.php' directory (only had single php file that wasn't being used for social media) in the template-parts folder
- 'homepage' sass file since it was empty
- Global console log (/assets/src/js/____global.js)
- “Define CPT block templates” due to errors if one of the preset blocks is removed (/functions.php)
- All commented out console.logs from block php files (/template-parts/blocks/)

____________________________________________________________

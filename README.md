# Banshee Starter WordPress Theme by Banshee Studio

For info on the site theme, see the [Theme README](wordpress/wp-content/themes/bansheeStarter/README.md).

## General Information

- Production Url:
- Platform/CMS: WordPress: [LOCAL LINK](silphium.lndo)
- Code stored on github: [GITHUB LINK](https://github.com/kendrak922/silphium)
- figma Designs:
- domain :[DOMAIN](https://www.silphiumcollective.com/)

## Getting Started

- clone Release/Develop branch
- create env.php file in the /wordpress directory
  - if copying search lndo and replace with new site url
- create wp-config.php file in the /wordpress directory
- Run npm install in the theme directory
- Run composer install in the theme directory
- Run gulp in the theme directory

### Lando install first time

- lando start
- install database
  - lando db-import <name>
- cd /wordpress
  - lando wp core download --skip-content

### Theme Setup
- import ACF fields
- add colors to admin-styles.css, variables, functions--gutenburg-settings.php, dev guide, and style guide.
- Update native wordpress variables in theme.json
- convert fonts: https://transfonter.org/, add font faces, assign primary and secondary fonts
- Check global elements like buttons


### Plugins
- ACF

todo---

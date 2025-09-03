<?php

$env_wp_home = getenv('WP_HOME') ?: "https://silphium.lndo.site";
$env_wp_siteurl = getenv('WP_SITEURL') ?: "https://silphium.lndo.site";
$env_wp_dbname = getenv('DB_NAME') ?: "wordpress";
$env_wp_dbuser = getenv('DB_USER') ?: "wordpress";
$env_wp_dbpassword = getenv('DB_PASSWORD') ?: "wordpress";
$env_wp_dbhost = getenv('DB_HOST') ?: "database";

define('WP_HOME', $env_wp_home);
define('WP_SITEURL', $env_wp_siteurl);
define('DB_NAME', $env_wp_dbname);
define('DB_USER', $env_wp_dbuser);
define('DB_PASSWORD', $env_wp_dbpassword);
define('DB_HOST', $env_wp_dbhost);

$env_wp_debug = false;
define('WP_DEBUG', $env_wp_debug);
$env_wp_debugdisplay = false;
define('WP_DEBUG_DISPLAY', $env_wp_debugdisplay);
$env_wp_debuglog = false;
define('WP_DEBUG_LOG', $env_wp_debuglog);


define('WP_MEMORY_LIMIT', '2G');

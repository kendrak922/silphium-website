<?php

/**
 * GetIconMarkup
 * - pulls the svg content from theme's dist/imgs/icons folder for inline 
 * 
 * 
 * @param array $name - pass the name of the icon's filename
 * @example
 * echo GetIconMarkup('icon-search');
 */

function GetIconMarkup($name)
{
    return file_get_contents(ABSPATH . "wp-content/themes/bansheeStarter/assets/dist/imgs/icons/$name.svg");
}

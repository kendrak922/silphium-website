<?php

/*************************************************************
    MENU WALKER: CUSTOM WALKER FUNCTION
 *************************************************************/

 /****** MAIN MENU *******/
class bansheeStarter_nav_walker extends Walker_Nav_menu
{
  public $current_title = '';
  function start_lvl(&$output, $depth = 0, $args = array())
  {
    // Depth-dependent classes.
    $indent = ($depth > 0  ? str_repeat("\t", $depth) : ''); // code indent
    $display_depth = ($depth + 1); // because it counts the first submenu as 0
    $classes = array(
      'sub-menu',
      ($display_depth >= 2 ? 'sub-sub-menu' : ''),
      'menu-depth-' . $display_depth
    );
    $class_names = implode(' ', $classes);

    // Build HTML for output.
    // $output .= "\n" . $indent . '<div class="' . $class_names . '"><div class="sub-menu__head"><button class="sub-menu__back"> <- </button>'.$this->current_title.'</div><ul>' . "\n";
    $output .= "\n" . $indent . '<div class="' . $class_names . '"><ul>' . "\n";
  }

  function end_lvl( &$output, $depth = 0, $args = null ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent  = str_repeat( $t, $depth );
    $output .= "$indent</ul></div>{$n}";
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
  {
    $title = $item->title;
    $permalink = $item->url;
    $target = $item->target;
    // $url = $item->xfn;
    $classList = $item->classes ?? [];
    $is_heading = in_array('menu-heading', $classList) ? true : false;
    $has_children = in_array('menu-item-has-children', $classList) ? true : false;
    $is_current = in_array('current_page_item', $classList) ? true : false;
    $is_current_ancestor = in_array('current-menu-ancestor', $classList) ? true : false;

    $output .= "<li class='" . implode(" ", $classList) . "' data-id='" . $title . "'>";

    // turn to button if nested links and has menu-heading class
    if ($is_heading && $has_children) {
      $output .= '<button class="' . implode(" ", $classList) . ' sub-menu--toggle-contain" data-btn="toggle" aria-label="Show submenu for '.$title.'">';
    } else {
      if($has_children) { $output .= '<div class="sub-menu--toggle-contain">';}
      $output .= '<a href="' . $permalink . '">';
    }

    $output .= $title;
    $this->current_title = $title;
    if($is_current){
      $output .= '<span class="sr-only">(active)</span>';
    }else if($is_current_ancestor){
      $output .= '<span class="sr-only">(active parent)</span>';
    }

    // if($target === "_blank"){
    //   $output .= '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    //     <path d="M6 6H14V14" stroke="#1A1818" stroke-width="1.5"/>
    //     <path d="M14 6L6 14" stroke="#1A1818" stroke-width="1.5"/>
    //     <rect x="0.25" y="0.25" width="19.5" height="19.5" rx="1.75" stroke="#1A1818" stroke-width="0.5"/>
    //     </svg>';
    // }

    if ($is_heading && $has_children) {
      $output .= '<span class="menu-item__subnav-toggle"><span class="sr-only">Show submenu for '.$title.'</span></span></button>';
    } elseif ($has_children) {
      $output .= '</a><button class="menu-item__subnav-toggle" data-btn="toggle" aria-label="show submenu for '.$title.'"><span class="sr-only">show submenu for '.$title.'</span></button></div>';
    } else {
      $output .= '</a>';
    }
  }
}


/****** FOOTER MENU *******/
class bansheeStarter_nav_walker_footer extends Walker_Nav_menu{
  public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
    // Restores the more descriptive, specific name for use within this method.
    $menu_item = $data_object;

    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

    $classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
    $classes[] = 'menu-item-' . $menu_item->ID;

    /**
     * Filters the arguments for a single nav menu item.
     *
     * @since 4.4.0
     *
     * @param stdClass $args      An object of wp_nav_menu() arguments.
     * @param WP_Post  $menu_item Menu item data object.
     * @param int      $depth     Depth of menu item. Used for padding.
     */
    $args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

    /**
     * Filters the CSS classes applied to a menu item's list item element.
     *
     * @since 3.0.0
     * @since 4.1.0 The `$depth` parameter was added.
     *
     * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
     * @param WP_Post  $menu_item The current menu item object.
     * @param stdClass $args      An object of wp_nav_menu() arguments.
     * @param int      $depth     Depth of menu item. Used for padding.
     */
    $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );

    /**
     * Filters the ID attribute applied to a menu item's list item element.
     *
     * @since 3.0.1
     * @since 4.1.0 The `$depth` parameter was added.
     *
     * @param string   $menu_item_id The ID attribute applied to the menu item's `<li>` element.
     * @param WP_Post  $menu_item    The current menu item.
     * @param stdClass $args         An object of wp_nav_menu() arguments.
     * @param int      $depth        Depth of menu item. Used for padding.
     */
    $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );

    $li_atts          = array();
    $li_atts['id']    = ! empty( $id ) ? $id : '';
    $li_atts['class'] = ! empty( $class_names ) ? $class_names : '';

    /**
     * Filters the HTML attributes applied to a menu's list item element.
     *
     * @since 6.3.0
     *
     * @param array $li_atts {
     *     The HTML attributes applied to the menu item's `<li>` element, empty strings are ignored.
     *
     *     @type string $class        HTML CSS class attribute.
     *     @type string $id           HTML id attribute.
     * }
     * @param WP_Post  $menu_item The current menu item object.
     * @param stdClass $args      An object of wp_nav_menu() arguments.
     * @param int      $depth     Depth of menu item. Used for padding.
     */
    $li_atts       = apply_filters( 'nav_menu_item_attributes', $li_atts, $menu_item, $args, $depth );
    $li_attributes = $this->build_atts( $li_atts );

    $output .= $indent . '<li' . $li_attributes . '>';

    $atts           = array();
    $atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
    $atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
    if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
      $atts['rel'] = 'noopener';
    } else {
      $atts['rel'] = $menu_item->xfn;
    }

    if ( ! empty( $menu_item->url ) ) {
      if ( get_privacy_policy_url() === $menu_item->url ) {
        $atts['rel'] = empty( $atts['rel'] ) ? 'privacy-policy' : $atts['rel'] . ' privacy-policy';
      }

      $atts['href'] = $menu_item->url;
    } else {
      $atts['href'] = '';
    }

    $atts['aria-current'] = $menu_item->current ? 'page' : '';

    /**
     * Filters the HTML attributes applied to a menu item's anchor element.
     *
     * @since 3.6.0
     * @since 4.1.0 The `$depth` parameter was added.
     *
     * @param array $atts {
     *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
     *
     *     @type string $title        Title attribute.
     *     @type string $target       Target attribute.
     *     @type string $rel          The rel attribute.
     *     @type string $href         The href attribute.
     *     @type string $aria-current The aria-current attribute.
     * }
     * @param WP_Post  $menu_item The current menu item object.
     * @param stdClass $args      An object of wp_nav_menu() arguments.
     * @param int      $depth     Depth of menu item. Used for padding.
     */
    $atts       = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );
    $attributes = $this->build_atts( $atts );

    /** This filter is documented in wp-includes/post-template.php */
    $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

    /**
     * Filters a menu item's title.
     *
     * @since 4.4.0
     *
     * @param string   $title     The menu item's title.
     * @param WP_Post  $menu_item The current menu item object.
     * @param stdClass $args      An object of wp_nav_menu() arguments.
     * @param int      $depth     Depth of menu item. Used for padding.
     */
    $title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

    $item_output  = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .=  $title;
    $item_output .= '</a>';
    $item_output .= $args->after;

    /**
     * Filters a menu item's starting output.
     *
     * The menu item's starting output only includes `$args->before`, the opening `<a>`,
     * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
     * no filter for modifying the opening and closing `<li>` for a menu item.
     *
     * @since 3.0.0
     *
     * @param string   $item_output The menu item's starting HTML output.
     * @param WP_Post  $menu_item   Menu item data object.
     * @param int      $depth       Depth of menu item. Used for padding.
     * @param stdClass $args        An object of wp_nav_menu() arguments.
     */
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
  }

}
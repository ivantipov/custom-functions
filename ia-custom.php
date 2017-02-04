<?php
/**
 * Plugin Name: Theme Custom Code
 * Description: A collection of custom functions for WordPress projects.
 * Author: Ivan Antipov
 * Author URI: https://ivantipov.com
 * Version: 0.1.0
 */

//    _                   _
//   | |   ___  __ _ __ _(_)_ _  __ _
//   | |__/ _ \/ _` / _` | | ' \/ _` |
//   |____\___/\__, \__, |_|_||_\__, |
//             |___/|___/       |___/
function _log($message) {
  error_log(print_r($message, true));
}


//     ___        _                            _     _      _         _
//    / __|  _ __| |_ ___ _ __    _ __  ___ __| |_  | |__ _| |__  ___| |
//   | (_| || (_-<  _/ _ \ '  \  | '_ \/ _ (_-<  _| | / _` | '_ \/ -_) |
//    \___\_,_/__/\__\___/_|_|_| | .__/\___/__/\__| |_\__,_|_.__/\___|_|
//                               |_|
function ia_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News';
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News';
    $submenu['edit.php'][16][0] = 'Tags';
}
function ia_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'News';
    $labels->singular_name = 'News';
    $labels->add_new = 'Add News';
    $labels->add_new_item = 'Add News';
    $labels->edit_item = 'Edit News';
    $labels->new_item = 'News';
    $labels->view_item = 'View News';
    $labels->search_items = 'Search News';
    $labels->not_found = 'No News found';
    $labels->not_found_in_trash = 'No News found in Trash';
    $labels->all_items = 'All News';
    $labels->menu_name = 'News';
    $labels->name_admin_bar = 'News';
}
add_action('admin_menu', 'ia_change_post_label');
add_action('init', 'ia_change_post_object');


//    _____    _                _    _ _
//   |_   _| _(_)_ __   __ __ _| |_ (_) |_ ___ ____ __  __ _ __ ___
//     | || '_| | '  \  \ V  V / ' \| |  _/ -_|_-< '_ \/ _` / _/ -_)
//     |_||_| |_|_|_|_|  \_/\_/|_||_|_|\__\___/__/ .__/\__,_\__\___|
//                                               |_|
function trim_all($str, $what = NULL, $with = ' ') {
  if ($what === NULL) {
    //  Character      Decimal      Use
    //  "\0"            0           Null Character
    //  "\t"            9           Tab
    //  "\n"           10           New line
    //  "\x0B"         11           Vertical Tab
    //  "\r"           13           New Line in Mac
    //  " "            32           Space

    $what = "\\x00-\\x20"; // all white-spaces and control chars
  }

  return trim(preg_replace("/[".$what."]+/" , $with , $str), $what);
}


//    ___                                      _ _ _
//   |_ _|_ __  __ _ __ _ ___   __ _ _  _ __ _| (_) |_ _  _
//    | || '  \/ _` / _` / -_) / _` | || / _` | | |  _| || |
//   |___|_|_|_\__,_\__, \___| \__, |\_,_\__,_|_|_|\__|\_, |
//                  |___/         |_|                  |__/
add_filter('jpeg_quality', create_function('', 'return 100;'));


//    ___                              _    __ _     _    _                  _
//   | _ \__ _ _______ __ _____ _ _ __| |  / _(_)___| |__| |  ___ _ __  _ __| |_ _  _
//   |  _/ _` (_-<_-< V  V / _ \ '_/ _` | |  _| / -_) / _` | / -_) '  \| '_ \  _| || |
//   |_| \__,_/__/__/\_/\_/\___/_| \__,_| |_| |_\___|_\__,_| \___|_|_|_| .__/\__|\_, |
//                                                                     |_|       |__/
function kill_wp_attempt_focus_start() {
  ob_start('kill_wp_attempt_focus_replace');
}
add_action('login_form', 'kill_wp_attempt_focus_start');

function kill_wp_attempt_focus_replace($html) {
  return preg_replace("/d.value = '';/", '', $html);
}

function kill_wp_attempt_focus_end() {
  ob_end_flush();
}
add_action('login_footer', 'kill_wp_attempt_focus_end');


//    __  __                                                   _    _ _
//   |  \/  |___ _ _ _  _   _ _ ___ _ __  _____ _____  __ __ _| |_ (_) |_ ___ ____ __  __ _ __ ___
//   | |\/| / -_) ' \ || | | '_/ -_) '  \/ _ \ V / -_) \ V  V / ' \| |  _/ -_|_-< '_ \/ _` / _/ -_)
//   |_|  |_\___|_||_\_,_| |_| \___|_|_|_\___/\_/\___|  \_/\_/|_||_|_|\__\___/__/ .__/\__,_\__\___|
//                                                                              |_|
function remove_menu_item_whitespace($items, $args) {
  return preg_replace('/>(\s|\n|\r)+</', '><', $items);
}
add_filter('wp_nav_menu_items', 'remove_menu_item_whitespace', 10, 2);


//    ___        _ _            _          _   _           _                  _
//   | _ \___ __| (_)_ _ ___ __| |_   __ _| |_| |_ __ _ __| |_  _ __  ___ _ _| |_ ___
//   |   / -_) _` | | '_/ -_) _|  _| / _` |  _|  _/ _` / _| ' \| '  \/ -_) ' \  _(_-<
//   |_|_\___\__,_|_|_| \___\__|\__| \__,_|\__|\__\__,_\__|_||_|_|_|_\___|_||_\__/__/
//
function redirect_attachment_page() {
  if (is_attachment()) {
    global $post;
    if ($post && $post->post_parent) {
      wp_redirect(esc_url(get_permalink($post->post_parent)), 301);
      exit;
    } else {
      wp_redirect(esc_url(home_url('/')), 301);
      exit;
    }
  }
}
add_action('template_redirect', 'redirect_attachment_page');

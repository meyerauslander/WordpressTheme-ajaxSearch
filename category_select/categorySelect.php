<?php

define('WPCCI_WP_VERSION',      get_bloginfo('version'));
define('WPCCI_WP_MIN_VERSION',  3.5);
define('WPCCI_MIN_PHP_VERSION', '5.3.0');
define('WPCCI_PATH_BASE',       get_template_directory());
define('WPCCI_PATH_TEMPLATES',  WPCCI_PATH_BASE . '/category_select/templates/');

// no localization is currently needed.  load_theme_textdomain('wpcustom-category-image', WPCCI_PATH_BASE . '/category_select/' . '/languages'); 

include WPCCI_PATH_BASE . '/category_select/' . 'functions.php';
include WPCCI_PATH_BASE . '/category_select/' . 'class-WPCustomCategoryImage.php';

add_action('init', array('WPCustomCategoryImage', 'initialize'));

register_activation_hook(__FILE__, array('WPCustomCategoryImage', 'activate'));
register_deactivation_hook(__FILE__, array('WPCustomCategoryImage', 'deactivate'));
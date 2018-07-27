<?php

/*define constants for the search excerpt*/
define('MAUS_BEFORE_PHRASE',20);
define('MAUS_AFTER_PHRASE',80);
define('MAUS_MAX_LENGTH',500);
define('MAUS_TEXT_DOMAIN','mysearch');

/* include category selection functionality and classes needed for custom search output */
require(get_template_directory() . '/category_select/categorySelect.php');
require(get_template_directory() . '/classes/class-search_helper.php');
require(get_template_directory() . '/classes/class-excerpts.php');

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

// Localization Support as of now there is no need for it.  If it becomes applicable call load_theme_textdomain()


/*------------------------------------*\
	Functions
\*------------------------------------*/

// Load mysearch scripts 
function mysearch_header_scripts()
{   
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('mysearch_search', get_template_directory_uri() . '/js/ajaxsearch.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('mysearch_search'); // Enqueue it!
    }
}

// Load mysearch styles
function mysearch_styles()
{
    wp_register_style('mysearch', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('mysearch'); // Enqueue it!
}

// If Dynamic Sidebar Exists -- This creates an option to add widgets to the site: admin-->Appearance-->widgets
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Wide Area 1', MAUS_TEXT_DOMAIN),
        'description' => __('Description for this widget-area...', MAUS_TEXT_DOMAIN),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', MAUS_TEXT_DOMAIN),
        'description' => __('Description for this widget-area...', MAUS_TEXT_DOMAIN),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}


// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links
function mysearchwp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}




/*------------------------------------*\
	Actions 
\*------------------------------------*/

// Add Actions
add_action('init', 'mysearch_header_scripts'); // Add Custom Scripts to wp_head 
add_action('wp_enqueue_scripts', 'mysearch_styles'); // Add Theme Stylesheet
add_action('init', 'mysearchwp_pagination'); // Add our mysearch Pagination


?>

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

function mysearch_the_custom_logo() {
    if ( function_exists( 'the_custom_logo' ) ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            the_custom_logo();
        } else { //output the company name and the company division ?>
            <span class="company-name"> <?php echo get_theme_mod( "mysearch_company-name" ) . " "; ?> </span>
            <span class="company-division"><?php echo get_theme_mod( "mysearch_company-division" ); ?></span>
            <?php
        }   
    }
}

// Localization Support as of now there is no need for it.  If it becomes applicable call load_theme_textdomain()
add_theme_support( 'custom-logo' ); //allow the user to choose a logo 
//
//function themename_custom_logo_setup() {
//    $defaults = array(
//        'height'      => 100,
//        'width'       => 400,
//        'flex-height' => true,
//        'flex-width'  => true,
//        'header-text' => array( 'site-title', 'site-description' ),
//    );
//    add_theme_support( 'custom-logo', $defaults );
//}
//add_action( 'after_setup_theme', 'themename_custom_logo_setup' );
 
function mysearch_customize_register( $wp_customize ) {
    
//    If they want this infomoration in it's own section    
//    $wp_customize->add_section( 'mysearch_company_section' , array(
//        'title'      => __( 'Additional Company Info', 'mysearch' ),
//        'priority'   => 30,
//    ));
    
    
    $wp_customize->add_setting( 'mysearch_company-name', array());
    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize,
        'mysearch_company_control',
            array(
                'label'      => __( 'Company Name', MAUS_TEXT_DOMAIN ),
                'section'    => 'title_tagline',
                'settings'   => 'mysearch_company-name',
                'priority'   => 1
            )
        )
    );

    $wp_customize->add_setting( 'mysearch_company-division', array());
    $wp_customize->add_control( new WP_Customize_Control(
        $wp_customize,
        'mysearch_division_control',
            array(
                'label'      => __( 'Company Division', MAUS_TEXT_DOMAIN ),
                'section'    => 'title_tagline',
                'settings'   => 'mysearch_company-division',
                'priority'   => 1
            )
        )
    );  
    
    // ..repeat ->add_setting() and ->add_control() for mytheme_company-division
}

add_action( 'customize_register', 'mysearch_customize_register' );
                                       


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

// content filter to cause search to be on specifically content and not html entities
function maus_content_filter( $content ) {
    $content = html_entity_decode($content);
    // run your code on $content and
    return $content;
}
add_filter( 'the_content', 'maus_content_filter',999 );

// Load mysearch styles
function mysearch_styles()
{
    wp_register_style('mysearch', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('mysearch'); // Enqueue it!
}

// Load mysearch conditional styles
//function mysearch_conditional_styles()
//{
//    //for pages containting a toc commented out because is_toc_post() requires the loop
//    if (tstn_toc_widget::is_toc_post() && is_single()) {
//        wp_register_style('toc-page', get_template_directory_uri() . '/toc_post.css', array(), '1.0', 'all');
//        wp_enqueue_style('toc-page'); // Enqueue it!
//    }
//}


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
//add_action('wp_print_scripts', 'mysearch_conditional_styles'); // Add Conditional Page Styles:  not needed yet
add_action('init', 'mysearchwp_pagination'); // Add our mysearch Pagination


?>

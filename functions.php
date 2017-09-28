<?php
/*
 *  Author: Philippe Schild
 *  Custom functions, support, custom post types and more.
 */

// custom image sizes
add_image_size('image_grid_landscape', 0, 350);
add_image_size('image_grid_portrait', 350, 0);

// remove admin bar in frontend
function remove_admin_bar() {
    return false;
}
add_filter('show_admin_bar', 'remove_admin_bar');

// add menus
function register_menus() {
    register_nav_menus(
        array(
            'main_navigation' => __( 'Main Navigation' )
        )
    );
}
add_action('init', 'register_menus');

// register custom post type and taxonomies
function post_type_projects() {
    register_post_type(
        'timeline',
        array(
            'labels' => array(
                'name' => __( 'Timeline Einträge' ),
                'singular_name' => __( 'Timeline Eintrag' )
            ),
            'public' => true,
            'supports' => array(
                'title',
                'editor'
            ),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-clock'
        )
    );

    register_post_type(
        'project',
        array(
            'labels' => array(
                'name' => __( 'Projekte' ),
                'singular_name' => __( 'Projekt' )
            ),
            'public' => true,
            'supports' => array(
                'title',
                'editor'
            ),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-media-code'
        )
    );

    register_taxonomy( 'project_categories', 'project',
        array(
            'hierarchical' => true,
            'label' => __('Projekt-Kategorien'),
            'query_var' => 'project_category',
            'rewrite' => true,
            'show_in_rest' => true
        )
    );
}
add_action('init', 'post_type_projects');

// custom routes
include_once 'includes/menu-routes-controller.php';

function register_menu_routes() {
    $controller = new MenuRoutesController();
    $controller->register_routes();
}
add_action('rest_api_init', 'register_menu_routes');

// register shortcodes
function codeblock_shortcode( $atts, $content = null ) {
    $language = $atts['language'];
    $url = $atts['url'];
    $code = $atts['code'];

    if (!empty($url)) {
        $code = file_get_contents($url);
    }

    $saveCode = htmlspecialchars($code);

    return '<pre><code class="code-highlight ' . $language . '">' . $saveCode . '</code></pre>';
}
add_shortcode( 'codeblock', 'codeblock_shortcode' );

// enable post templates at ACF
include_once 'includes/acf-post-template-manager.php';
$manager = new AcfPostTemplateManager();
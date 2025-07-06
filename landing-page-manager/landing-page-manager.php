<?php
/**
 * Plugin Name: Landing Page Manager (Internal)
 * Description: MVP plugin to manage multi-client landing pages with subdomain routing.
 * Version: 0.1
 * Author: Odell Duppins
 * Text Domain: landing-page-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'LP_MANAGER_PLUGIN_URL' ) ) {
    define( 'LP_MANAGER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'LP_MANAGER_PLUGIN_PATH' ) ) {
    define( 'LP_MANAGER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Load composer autoloader first (needed for LPManager namespace classes)
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    error_log('Composer autoload missing');
}

add_action( 'after_setup_theme', function() {
    \Carbon_Fields\Carbon_Fields::boot();
});

use LPManager\Plugin;

// Initialize plugin
add_action( 'plugins_loaded', function() {
    Plugin::init();
});

// Register activation hook (must be outside class)
register_activation_hook( __FILE__, [ Plugin::class, 'activate' ] );

    // Start output buffering early for REST API requests
    add_action('rest_api_init', function () {
        ob_start();
    }, 0);

    // Register REST route
    add_action('rest_api_init', function () {
        register_rest_route('lpmanager/v1', '/dashboard-data', [
            'methods'  => 'GET',
            'callback' => [LPManager\admin\Admin_Dashboard::class, 'get_dashboard_data'],
            'permission_callback' => function() {
    			return current_user_can( 'manage_options' ); // Administrator or equivalent
		}
        ]);
    }, 10); // priority 10 ensures this runs after ob_start

add_action( 'rest_api_init', function () {
    register_rest_route( 'lpmanager/v1', '/get-brand-colors', [
        'methods'  => 'POST',
        'callback' => [ 'LPManager\taxonomy\Client_Taxonomy', 'handle_brandfetch_request' ],
        'permission_callback' => function() {
            return current_user_can( 'manage_options' ); // or adjust for your capability
        }
    ] );
} );

    // Clean output buffer before REST response dispatch
    add_filter('rest_post_dispatch', function ($response, $server, $request) {
        if (ob_get_length()) {
            ob_end_clean();
        }
        return $response;
    }, 100, 3);

add_action( 'template_redirect', function() {
    if ( is_singular( 'landing_page' ) ) {

        // Remove Site Kit's head scripts
        remove_action( 'wp_head', 'googlesitekit_print_head_scripts', 20 );

        // Remove Site Kit's footer scripts
        remove_action( 'wp_footer', 'googlesitekit_print_footer_scripts', 20 );

        // Optional: Remove any other head/footer hooks Site Kit might be using
        // If other third-party plugins hook into wp_head/wp_footer for analytics, you might clear them too:
        // remove_all_actions( 'wp_head' );
        // remove_all_actions( 'wp_footer' );
    }
    
    add_action( 'wp', function() {
        if ( is_singular( 'landing_page' ) ) {
            global $wp_filter;
            error_log( print_r( $wp_filter['wp_head'], true ) );
            error_log( print_r( $wp_filter['wp_footer'], true ) );
        }
    });
});

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( 'edit-tags.php' === $hook && isset( $_GET['taxonomy'] ) && 'client' === $_GET['taxonomy'] ) {
        wp_enqueue_script( 'client-brandfetch', plugin_dir_url( __FILE__ ) . 'assets/js/client-brandfetch.js', [ 'jquery' ], '1.0', true );
        wp_localize_script( 'client-brandfetch', 'lpmanager_ajax', [
            'rest_url' => rest_url( 'lpmanager/v1/get-brand-colors' ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
        ] );
    }
} );

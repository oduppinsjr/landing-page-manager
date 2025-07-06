<?php
/**
 * Class Admin_Dashboard
 * 
 * Handles the 'client' taxonomy for Landing Pages,
 * including term meta, admin columns, and admin UI customizations.
 */
namespace LPManager\admin;

class Admin_Dashboard {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'register_menu_page' ] );
        
		// Add dashboard widget on WP Dashboard screen
        add_action('wp_dashboard_setup', [ __CLASS__, 'add_dashboard_widgets' ]);

        // Add submenu for plugin dashboard
        add_action( 'admin_menu', [ __CLASS__, 'register_dashboard_page' ] );
        add_action( 'admin_menu', [ __CLASS__, 'add_dashboard_submenu' ] );
        
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
    }
	
    public static function enqueue_assets() {
        wp_enqueue_script(
            'lpmanager-dashboard',
            plugin_dir_url(__FILE__) . '../assets/js/dashboard.js',
            ['chart-js-handle'], // make sure Chart.js is a dependency
            '1.0.0',
            true
        );

        // Pass the REST API URL to the JS script
        wp_localize_script('lpmanager-dashboard', 'lpmanagerDashboard', [
            'apiUrl' => rest_url('lpmanager/v1/dashboard-data'),
            'nonce'  => wp_create_nonce('wp_rest'),
        ]);
    }
    
    public static function enqueue_admin_assets($hook) {
    	if (strpos($hook, 'lp_dashboard') === false) {
            return;
        }

        // Load Chart.js from CDN
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            '4.4.1',
            true
        );

        // Load your dashboard JS, with Chart.js as a dependency
        wp_enqueue_script(
            'lp-dashboard-js',
            LP_MANAGER_PLUGIN_URL . 'assets/js/dashboard.js',
            ['chartjs'],
            '1.0.0',
            true
        );

        // Localize the REST API URL and Nonce
        wp_localize_script(
            'lp-dashboard-js',
            'lpmanagerDashboard',
            [
                'apiUrl' => rest_url('lpmanager/v1/dashboard-data'),
                'nonce'  => wp_create_nonce('wp_rest')
            ]
        );

        // Optional: Load dashboard CSS
        wp_enqueue_style(
            'lp-dashboard-css',
            LP_MANAGER_PLUGIN_URL . 'assets/css/dashboard.css',
            [],
            '1.0.0'
        );
    }
    
    public static function get_dashboard_data( $request ) {
        // Landing page counts
        $counts = wp_count_posts( 'landing_page' );
        $data['landing_pages'] = [
            'publish' => (int) $counts->publish,
            'draft'   => (int) $counts->draft,
            'pending' => (int) $counts->pending
        ];

        // Client taxonomy count (used outside charts)
        $data['clients'] = wp_count_terms( 'client', [ 'hide_empty' => false ] );

        // Keywords taxonomy counts
        $terms = get_terms( [ 'taxonomy' => 'keyword', 'hide_empty' => false ] );
        $data['keywords'] = [];
        foreach ( $terms as $term ) {
            $data['keywords'][] = [
                'label' => $term->name,
                'count' => $term->count
            ];
        }

        // Get all landing pages
        $pages = get_posts( [
            'post_type'      => 'landing_page',
            'posts_per_page' => -1
        ] );

        // Visits per page (post meta)
        $visits_per_page = [];
        $conversions_per_page = [];
        foreach ( $pages as $page ) {
            $visits = (int) get_post_meta( $page->ID, '_lpmanager_page_views', true );
            $conversions = (int) get_post_meta( $page->ID, '_lpmanager_conversions', true );
            $visits_per_page[ $page->post_title ] = $visits;
            $conversions_per_page[ $page->post_title ] = $conversions;
        }
        $data['visits_per_page'] = $visits_per_page;
        $data['conversions_per_page'] = $conversions_per_page;

        // Total keyword count (optional)
        $data['keyword_count'] = count( $terms );

        // Daily visits (option, fallback to empty array)
        $daily_visits = get_option( 'lpmanager_daily_visits', [] );
        $data['daily_visits'] = $daily_visits ?: [];

        // Daily conversions (option, fallback to empty array)
        $daily_conversions = get_option( 'lpmanager_daily_conversions', [] );
        $data['daily_conversions'] = $daily_conversions ?: [];

        return new \WP_REST_Response( $data, 200 );
    }

    public static function register_menu_page() {
        add_menu_page(
            'Landing Pages',
            'Landing Pages',
            'manage_options',
    		'lp_dashboard',
            '',  // No callback, CPT listing handles output
            'dashicons-media-document',
    		21
        );
		
        // Submenu: Dashboard (required for top-level menu to show submenu)
        add_submenu_page(
            'lp_dashboard',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'lp_dashboard',
            [ __CLASS__, 'render_dashboard_page' ]
        );
        
        // Submenu: Landing Pages CPT listing
        add_submenu_page(
            'lp_dashboard',
            'Landing Pages',
            'Landing Pages',
            'edit_posts',
            'edit.php?post_type=landing_page'
        );

        // Submenu: Clients taxonomy
        add_submenu_page(
            'lp_dashboard',
            'Clients',
            'Clients',
            'manage_options',
            'edit-tags.php?taxonomy=client&post_type=landing_page'
        );
        
        // Submenu: Keyword taxonomy
        add_submenu_page(
            'lp_dashboard',
            'Keywords',
            'Keywords',
            'manage_options',
            'edit-tags.php?taxonomy=keyword&post_type=landing_page'
        );
        
    }
	
    public static function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'lp_dashboard_widget',
            __( 'Landing Page Manager Overview', 'landing-page-manager' ),
            [ __CLASS__, 'render_dashboard_widget' ]
        );
    }

    public static function render_dashboard_widget() {
        echo '<p>This is your landing page overview widget.</p>';
    }
    
    public static function register_dashboard_page() {
        add_submenu_page(
            'landing_page_manager', // parent slug (your top-level menu slug)
            __( 'Dashboard', 'landing-page-manager' ),
            __( 'Dashboard', 'landing-page-manager' ),
            'manage_options',
            'lp_dashboard',
            [ __CLASS__, 'render_dashboard_page' ]
        );
    }
    
    public static function render_settings_page() {
	    echo '<div class="wrap">';
	    echo '<h1>' . esc_html__( 'Landing Page Manager Settings', 'landing-page-manager' ) . '</h1>';
	    echo '<p>Use the settings below to configure global plugin behavior.</p>';
	    do_action( 'carbon_fields_container_lp_dashboard' ); // optionally trigger a custom hook if needed
	    echo '</div>';

    }
    
    public static function add_dashboard_submenu() {
        add_submenu_page(
            'edit.php?post_type=landing_page', // Parent slug (this attaches it to the Landing Pages menu)
            __( 'Dashboard', 'landing-page-manager' ), // Page title
            __( 'Dashboard', 'landing-page-manager' ), // Menu label
            'manage_options', // Capability
            'lp_dashboard', // Menu slug
            [ __CLASS__, 'render_dashboard_page' ] // Callback function
        );
    }

    public static function render_dashboard_page() {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Landing Page Manager Dashboard', 'landing-page-manager' ) . '</h1>';

        // Totals section (outside charts)
        $landing_pages_count = wp_count_posts( 'landing_page' );
        $clients_count       = wp_count_terms( 'client', [ 'hide_empty' => false ] );
        $total_landing_pages = intval( $landing_pages_count->publish ) + intval( $landing_pages_count->draft ) + intval( $landing_pages_count->pending );

        echo '<h2>Quick Actions</h2>';
        echo '<div>';
        echo '<a href="' . esc_url(admin_url('post-new.php?post_type=landing_page')) . '" class="button button-primary">Add New Landing Page</a> ';
        echo '<a href="' . esc_url(admin_url('edit-tags.php?taxonomy=client')) . '" class="button">Manage Clients</a>';
        echo '</div>';

        echo '<h2>Totals</h2>';
        echo '<div style="display: flex; gap: 20px;">';
        echo '<div style="flex:1; text-align:center; background:#fefefe; padding:15px; border:1px solid #ccc;">
                <h3>Total Landing Pages</h3>
                <p style="font-size: 24px;">' . esc_html($total_landing_pages) . '</p>
              </div>';
        echo '<div style="flex:1; text-align:center; background:#fefefe; padding:15px; border:1px solid #ccc;">
                <h3>Total Clients</h3>
                <p style="font-size: 24px;">' . esc_html($clients_count) . '</p>
              </div>';
        echo '</div>';

        // Recent landing pages
        $recent_pages = get_posts( [
            'post_type'      => 'landing_page',
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );

        echo '<h2>Recent Landing Pages</h2>';
        if ( empty( $recent_pages ) ) {
            echo '<p>No recent landing pages found.</p>';
        } else {
            echo '<ul>';
            foreach ( $recent_pages as $page ) {
                $edit_link = get_edit_post_link( $page->ID );
                echo '<li><a href="' . esc_url( $edit_link ) . '">' . esc_html( get_the_title( $page ) ) . '</a> (' . esc_html( get_post_status( $page ) ) . ')</li>';
            }
            echo '</ul>';
        }

        // Analytics charts grid
        echo '<h2>Landing Page Analytics</h2>';
        echo '<div style="
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            align-items: start;
        ">';

        // Chart canvases matching JS IDs
        $chart_ids = [
            'landingPageChart',
            'visitsPerPageChart',
            'visitsPerKeywordChart',
            'dailyVisitsChart',
            'topConvertersChart',
            'keywordChart'
        ];

        foreach ( $chart_ids as $id ) {
            echo '<div style="background: #fff; border: 1px solid #ccc; padding: 15px; box-sizing: border-box;">';
            echo '<canvas id="' . esc_attr( $id ) . '" width="400" height="300"></canvas>';
            echo '</div>';
        }

        echo '</div>';

        echo '</div>';
    }

}

Admin_Dashboard::init();

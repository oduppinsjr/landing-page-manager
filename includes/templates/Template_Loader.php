<?php

namespace LPManager\templates;

class Template_Loader {

    public static function init() {
        // Set template early, before CF fields registration
        add_action( 'template_redirect', [ __CLASS__, 'set_template_for_landing_page' ], 5 );

        // Load template-specific fields during Carbon Fields registration
        add_action( 'carbon_fields_register_fields', [ __CLASS__, 'load_template_fields' ] );

        // Override the template include for landing pages
        add_filter( 'template_include', [ __CLASS__, 'load_template' ], 99 );
    }

    /**
     * Set active template for landing pages
     */
    public static function set_template_for_landing_page() {
        if ( is_singular( 'landing_page' ) ) {
            global $post;
            if ( $post ) {
                $template_slug = carbon_get_post_meta( $post->ID, 'lpmanager_active_template' );
                if ( empty( $template_slug ) ) {
                    $template_slug = 'whiterail-ai';
                }
                Template_Manager::set_current_template( $template_slug );
            }
        }
    }

    /**
     * Load template-specific Carbon Fields definitions
     */
    public static function load_template_fields() {
        // Use default if nothing is set yet (for admin screens)
        $template_slug = Template_Manager::get_current_template();
        if ( empty( $template_slug ) ) {
            $template_slug = 'whiterail-ai';
        }

        $fields_file = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' . $template_slug . '/lib/fields/landing-page-fields.php';
        if ( file_exists( $fields_file ) ) {
            require_once $fields_file;
        }
    }

    /**
     * Load template file for front-end display
     */
    public static function load_template( $template ) {
        if ( is_singular( 'landing_page' ) ) {
            $template_slug = Template_Manager::get_current_template();
            if ( empty( $template_slug ) ) {
                $template_slug = 'whiterail-ai';
            }

            $template_dir  = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' . $template_slug . '/';
            $template_file = $template_dir . 'single-landing_page.php';

            return file_exists( $template_file )
                ? $template_file
                : self::get_custom_404_template();
        }

        if ( get_query_var( 'force_lp_404' ) ) {
            return self::get_custom_404_template();
        }

        return $template;
    }

    private static function get_custom_404_template() {
        $template_404 = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/404.php';
        return file_exists( $template_404 ) ? $template_404 : get_404_template();
    }
}

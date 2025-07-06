<?php
namespace LPManager\admin;

class Tracking_Manager {

    public static function init() {
        add_action('template_redirect', [__CLASS__, 'increment_page_view']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_tracking_scripts']);
        add_action('wp_ajax_lpmanager_record_conversion', [__CLASS__, 'record_conversion']);
        add_action('wp_ajax_nopriv_lpmanager_record_conversion', [__CLASS__, 'record_conversion']);
    }

    public static function increment_page_view() {
        if (is_singular('landing_page')) {
            $post_id = get_the_ID();
            $views = (int) get_post_meta($post_id, '_lpmanager_page_views', true);
            update_post_meta($post_id, '_lpmanager_page_views', $views + 1);
        }
    }

    public static function enqueue_tracking_scripts() {
        if (is_singular('landing_page')) {
            wp_enqueue_script(
                'lpmanager-tracking',
                LP_MANAGER_PLUGIN_URL . 'assets/js/tracking.js',
                ['jquery'],
                '1.0',
                true
            );
            wp_localize_script('lpmanager-tracking', 'lpmanager_vars', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('lpmanager_nonce'),
            ]);
        }
    }


    public static function record_conversion() {
        check_ajax_referer('lpmanager_nonce', 'nonce');
        $post_id = absint($_POST['post_id']);
        $conversions = (int) get_post_meta($post_id, '_lpmanager_conversions', true);
        update_post_meta($post_id, '_lpmanager_conversions', $conversions + 1);
        wp_send_json_success('Conversion recorded.');
    }
}

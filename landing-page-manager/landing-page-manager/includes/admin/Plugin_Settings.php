<?php
namespace LPManager\admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use LPManager\templates\Template_Manager;

class Plugin_Settings {
    public static function init() {
        add_action( 'carbon_fields_register_fields', [ __CLASS__, 'register_settings' ] );
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_styles']);
    }

	public static function enqueue_admin_styles($hook) {
        // Load only on your plugin's settings page
        error_log($hook);
		if ($hook !== 'landing-pages_page_crb_carbon_fields_container_settings') {
            return;
        }

        wp_enqueue_style(
            'lpmanager-admin-styles',
            LP_MANAGER_PLUGIN_URL . 'assets/css/admin.css',
            [],
            '1.0.0'
        );
        
        wp_enqueue_script(
        	'lpmanager-admin-js', 
            LP_MANAGER_PLUGIN_URL . 'assets/js/admin.js', 
            ['jquery'], 
            '1.0', 
            true
        );
        
        wp_localize_script('lpmanager-admin-js', 'lpmanager_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('lpmanager_nonce'),
        ]);
    }
    
    public static function register_settings() {
        $templates = Template_Manager::get_templates();

        // Define the base URL for template images
        $template_url = plugin_dir_url(__DIR__ . '/../assets/templates/previews/');

        if (empty($templates)) {
            $templates['none'] = [
                'name' => 'No templates found',
                'description' => '',
                'image_url' => $template_url . 'default-preview.png',
            ];
        }

        // Prepare options array for radio_image field (HTML strings)
        $template_options = [];
        foreach ($templates as $slug => $info) {
            $template_options[$slug] = '<div style="display:flex; flex-direction: column; align-items:center; margin-bottom: 15px;">
                <strong style="margin-bottom: 6px; font-size: 16px;">' . esc_html($info['name']) . '</strong>
                <img src="' . esc_url($info['image_url']) . '" alt="' . esc_attr($info['name']) . '" style="width:150px; height:auto; border:1px solid #ccc; margin-bottom: 6px;" />
                <p style="text-align:center; font-size: 12px; color: #666; margin: 0;">' . esc_html($info['description']) . '</p>
            </div>';
        }

        Container::make('theme_options', __('Settings'))
            ->set_page_parent('lp_dashboard')
            ->add_fields([
                Field::make('separator', 'landing_page_separator', __('Landing Page Templates')),

                Field::make('html', 'template_selector_label', __('Select Landing Page Template'))
                    ->set_html('<p>Select the template you want all landing pages to use. Visual previews shown below.</p>'),

                Field::make('html', 'lpmanager_template_selector_and_upload', __('Landing Page Templates'))
                    ->set_html(self::render_template_selector_and_upload())
                    ->set_help_text('Select a template or upload a new one.'),

                Field::make('hidden', 'lpmanager_active_template')
                		->set_label(false)
						->set_default_value('whiterail-ai'),
						//->set_attribute('id', 'lpmanager, 'lpmanager_active_template'),

                Field::make('separator', 'conversion_tracking_separator', __('Conversion Tracking Settings')),

                Field::make('checkbox', 'lpmanager_enable_conversion_tracking', __('Enable Conversion Tracking'))
                    ->set_option_value(false)
                    ->set_help_text('Enable or disable conversion tracking globally for landing pages.'),

                Field::make('text', 'lpmanager_conversion_tracking_id', __('Conversion Tracking ID'))
                    ->set_help_text('Enter your tracking ID (e.g. Google Analytics, Facebook Pixel).')
                    ->set_conditional_logic([
                        [
                            'field' => 'lpmanager_enable_conversion_tracking',
                            'value' => true,
                            'compare' => '=',
                        ]
                    ]),
				Field::make('separator', 'api_keys_separator', __('API Keys')),

                Field::make('text', 'lpmanager_google_maps_api_key', __('Google Maps API Key'))
                    ->set_help_text('Enter your Google Maps JavaScript API key for use on landing pages.'),
                    
    
                Field::make('textarea', 'lpmanager_additional_scripts', __('Additional Scripts'))
                    ->set_help_text('Add any additional scripts (e.g., tracking or chat scripts) to load on all landing pages.'),
            ]);
    }

    public static function render_template_selector_and_upload() {
        $templates = Template_Manager::get_templates();
        //$active_template = get_option('lpmanager_active_template');
        $active_template = carbon_get_theme_option('lpmanager_active_template');

        $html = '<h2 style="margin-bottom: 10px;">Landing Page Templates</h2>';
        $html .= '<div class="lpmanager-template-grid">';

        if (empty($templates)) {
            $html .= '<div class="lpmanager-template-card empty">';
            $html .= '<p>No templates found.</p>';
            $html .= '<img src="' . esc_url(includes_url('images/media/default.png')) . '" style="max-width: 150px; opacity: 0.3;">';
            $html .= '</div>';
        } else {
            foreach ($templates as $slug => $info) {
                $selected_class = ($slug === $active_template) ? ' selected' : '';
                $html .= '<div class="lpmanager-template-card' . esc_attr($selected_class) . '" data-template="' . esc_attr($slug) . '">';
                $html .= '<strong>' . esc_html($info['name']) . '</strong>';
                $html .= '<img src="' . esc_url($info['image_url']) . '" alt="' . esc_attr($info['name']) . '">';
                $html .= '<p>' . esc_html($info['description']) . '</p>';
                $html .= '</div>';
            }
        }

        // Upload box
        $html .= '<label class="lpmanager-template-upload">';
        $html .= '<strong>Upload New Template</strong>';
        $html .= '<input type="file" id="lpmanager_template_upload" name="lpmanager_template_upload" style="display:none;" accept=".zip">';
        $html .= '</label>';

        $html .= '</div>';

        // Hidden input to track selected template
        $html .= '<input type="hidden" id="lpmanager_active_template" name="lpmanager_active_template" value="' . esc_attr($active_template) . '">';

        return $html;
    }


}
<?php

require_once __DIR__ . '/lib/helper-functions.php';
require_once __DIR__ . '/lib/classes/Browser.php';

function setup_fields() {
	require_once __DIR__ . '/lib/fields/post-metas.php';
}
add_action('init', 'setup_fields');

function enqueue_whiterail_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-datepicker' );

	$template_uri = plugin_dir_url( __FILE__ );

	wp_register_script(
		'whiterail-recruits-main',
		$template_uri . 'js/main.js',
		[ 'jquery' ],
		'1.0.0',
		true
	);

    wp_register_script( 
        'whiterail-recruits-wow', 
        $template_uri . '/js/wow.min.js', 
        [ 'jquery' ],
		'1.0.0',
        true 
    );
    
	wp_localize_script(
		'whiterail-recruits-main',
		'ajaxInfo',
		[ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ]
	);

	wp_enqueue_script( 'whiterail-recruits-main' );
    wp_enqueue_script( 'whiterail-recruits-wow' );
}
add_action('wp_enqueue_scripts', 'enqueue_whiterail_scripts');

function enqueue_whiterail_styles() {
	$template_uri = plugin_dir_url( __FILE__ );

	wp_enqueue_style( 'whiterail-animations', $template_uri . 'css/animate.min.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-font-awesome', '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'whiterail-google-fonts', '//fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap' );
	wp_enqueue_style( 'whiterail-bootstrap', $template_uri . 'css/bootstrap.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-swiper-bundle', $template_uri . 'css/swiper-bundle.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-fonts', $template_uri . 'css/fonts.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-header', $template_uri . 'css/header.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-footer', $template_uri . 'css/footer.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-main', $template_uri . 'css/main.css', [], '1.0.0' );
	wp_enqueue_style( 'whiterail-responsive', $template_uri . 'css/responsive.css', [], '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_whiterail_styles' );

function enqueue_global_fonts() {
    wp_enqueue_style( 'global-google-fonts', '//fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_global_fonts', 5 ); // priority 5 ensures it's early
<?php

// General Functions

function get_user_geo_data($ip = '') {
	if ( !$ip ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	$ch = curl_init();
	
	curl_setopt_array($ch, array(
		CURLOPT_URL => 'http://www.geoplugin.net/json.gp?ip=' . $ip,
		CURLOPT_RETURNTRANSFER => 1
	));

	$geo_data = json_decode( curl_exec($ch), 1 );

	return $geo_data;
}

function get_youtube_video_id_from_url($url) {
	preg_match('~watch\?v=([a-zA-Z0-9_-]*)~', $url, $video_id);

	return $video_id[1];
}

function get_video_embed_code_by_url($url) {
	if ( preg_match('~youtube\.com~', $url) ) {
		return '<iframe width="100%" src="https://www.youtube.com/embed/' . get_youtube_video_id_from_url( $url ) . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
	} else {
		preg_match('~(\d+)~', $url, $video_id);

		return '<iframe src="https://player.vimeo.com/video/' . $video_id[1] . '" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
	}
}

function get_attachment_src($att_id, $size = 'full-hd') {
	if ( wp_attachment_is_image($att_id) ) {
		$att = wp_get_attachment_image_src($att_id, $size);
		
		return $att[0];
	}

	return wp_get_attachment_url($att_id);
}

function get_template_part_with_params($path, array $params = array()) {
	extract( $params );

	include( locate_template( $path . '.php' ) );
}

function get_page_by_template($template) {
	$page = get_posts(
		array(
			'post_type' => 'any',
			'numberposts' => 1,
			'meta_key' => '_wp_page_template',
			'meta_value' => $template
		)
	);

	if ( $page ) {
		return $page[0];
	}
}

function get_page_link_by_template($template) {
	$page = get_page_by_template($template);
	
	if ( $page ) {
		return get_permalink( $page->ID );
	}
}

function get_page_link_by_slug($slug) {
	$page = get_page_by_path($slug);
	
	if ( $page ) {
		return get_permalink( $page->ID );
	}
}

function convert_phone_for_link($phone) {
	return preg_replace('~[^0-9]~', '', $phone);
}

function maybe_add_target_attr_to_link($link) {
	$link_parts = parse_url( $link );
	$site_link_parts = parse_url( get_bloginfo('url') );

	if ( $link_parts['host'] != $site_link_parts['host'] ) {
		return 'target="_blank"';
	}
}

function get_svg_inline($path, $style = '') {
	if ( stripos( $path, '://' ) !== FALSE ) {
		$path = str_replace( get_bloginfo('url'), ABSPATH, $path );
		$path = str_replace( '//', '/', $path );
	}

	$svg = file_get_contents($path);

	if ( $style ) {
		$svg = str_replace( '<svg ', '<svg style="' . $style . '" ', $svg );
	}

	return $svg;
}

function svg_inline($path, $style = '') {
	echo get_svg_inline($path, $style);
}

// Theme-specific functions here
function nv_print( $array = array() ){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function get_locations_info(){
    $terms = get_terms( 'location', array(
        'hide_empty' => false,
    ) );

    $terms_info = array();
    $count = 0;
    foreach ( $terms as $term ){
        $location_phone_number = carbon_get_term_meta($term->term_id, 'location_phone_number');
        $location_address = carbon_get_term_meta($term->term_id, 'location_address');
        $location_address_link = carbon_get_term_meta($term->term_id, 'location_address_link');

        $terms_info[$count]['title'] = $term->name;
        $terms_info[$count]['phone'] = $location_phone_number;
        $terms_info[$count]['address'] = $location_address;
        $terms_info[$count]['address_link'] = $location_address_link;
        $terms_info[$count]['slug'] = $term->slug;
        $terms_info[$count]['id'] = $term->term_id;

        $count++;
    }

    return $terms_info;
}

function get_location_by_slug($slug){
    $term = get_term_by('slug', $slug, 'location');

    $terms_info = array();
    $count = 0;
    $location_phone_number = carbon_get_term_meta($term->term_id, 'location_phone_number');
    $location_address = carbon_get_term_meta($term->term_id, 'location_address');

    $terms_info[$count]['title'] = $term->name;
    $terms_info[$count]['phone'] = $location_phone_number;
    $terms_info[$count]['address'] = $location_address;
    $terms_info[$count]['slug'] = $term->slug;
    $terms_info[$count]['id'] = $term->term_id;

    return $terms_info;
}
<?php
/*
Plugin Name: Year Ago Today
Version: 0.1-alpha
Description: Re-publish a post from a year ago today
Author: Daniel Bachhuber
Author URI: https://handbuilt.co
Plugin URI: PLUGIN SITE HERE
Text Domain: year-ago-today
Domain Path: /languages
*/

function year_ago_today() {
	$args = array(
		'post_type'          => 'post',
		'post_status'        => 'publish',
		'year'               => current_time( 'Y' ) - 1,
		'monthmon'           => current_time( 'm' ),
		'day'                => current_time( 'd' ),
		'posts_per_page'     => 1,
		);
	$args = apply_filters( 'year_ago_today_args', $args );
	$query = new WP_Query( $args );
	foreach( $query->posts as $post ) {
		$new_post = array(
			'post_title'       => $post->post_title,
			'post_status'      => $post->post_status,
			'post_content'     => $post->post_content,
			'post_excerpt'     => $post->post_excerpt,
			);
		$new_post = apply_filters( 'year_ago_today_insert_args', $new_post, $post );
		wp_insert_post( $new_post );
	}
}

function year_ago_today_setup_cron() {
	if ( ! wp_next_scheduled( 'year_ago_today' ) ) {
		wp_schedule_event( time(), 'daily', 'year_ago_today' );
	}
}
add_action( 'admin_init', 'year_ago_today_setup_cron' );

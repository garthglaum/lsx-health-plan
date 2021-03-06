<?php
/*
 * Plugin Name:	LSX Health Plan
 * Plugin URI:	https://github.com/lightspeeddevelopment/lsx-health-plan
 * Description:	A meal and workout plan, with recipes
 * Author:		LightSpeed
 * Version: 	1.0.0
 * Author URI: 	https://www.lsdev.biz/
 * License: 	GPL3
 * Text Domain: lsx-health-plan
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'LSX_HEALTH_PLAN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_HEALTH_PLAN_CORE', __FILE__ );
define( 'LSX_HEALTH_PLAN_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_HEALTH_PLAN_VER', '1.0.0' );

/* ======================= Below is the Plugin Class init ========================= */

require_once LSX_HEALTH_PLAN_PATH . '/classes/class-core.php';

/**
 * Remove unnesesary custom post types
 *
 * @return void
 */
function lsx_remove_extra_meta_box() {
	global $wp_meta_boxes;
	$all_post_types = [ 'plan', 'video', 'workout', 'tip', 'recipe', 'meal' ];
	remove_meta_box( 'wpseo_meta', $all_post_types, 'normal' );
	remove_meta_box( 'commentsdiv', $all_post_types, 'normal' );
	remove_meta_box( 'commentstatusdiv', $all_post_types, 'normal' );
	remove_meta_box( 'lsx_blocks_title_meta', $all_post_types, 'side' );
}
add_action( 'add_meta_boxes', 'lsx_remove_extra_meta_box', 100 );

/**
 * Create Login page with woocommerce shortcode if the page is not created
 */
if ( ( isset( $_GET['activated'] ) && wp_verify_nonce( sanitize_key( $_GET['activated'] ) ) ) && is_admin() ) {

	$new_page_title = 'Login';
	// Content to add spacing and woocommerce login shortcode
	$new_page_content  = '<!-- wp:lsx-blocks/lsx-container {"containerMaxWidth":600} -->
	<div style="background-color:transparent;padding-left:3%;padding-right:3%;padding-bottom:3%;padding-top:3%;margin-top:3%;margin-bottom:3%" class="wp-block-lsx-blocks-lsx-container aligncenter lsx-block-container"><div class="lsx-container-inside"><div class="lsx-container-content" style="max-width:600px"><!-- wp:paragraph -->
	<p></p>
	<!-- /wp:paragraph -->
	
	<!-- wp:shortcode -->
	[woocommerce_my_account]
	<!-- /wp:shortcode --></div></div></div>
	<!-- /wp:lsx-blocks/lsx-container -->';
	$new_page_template = '';

	$page_check = get_page_by_title( $new_page_title );
	$new_page   = array(
		'post_type'    => 'page',
		'post_title'   => $new_page_title,
		'post_content' => $new_page_content,
		'post_status'  => 'publish',
		'post_author'  => 1,
	);
	if ( ! isset( $page_check->ID ) ) {
		$new_page_id = wp_insert_post( $new_page );
		if ( ! empty( $new_page_template ) ) {
			update_post_meta( $new_page_id, '_wp_page_template', $new_page_template );
		}
	}
}

/**
 * Add Loing Logut Button to Main Menu
 *
 * @param [type] $items
 * @param [type] $args
 * @return void
 */
function lsx_add_login_logout_register_menu( $items, $args ) {
	if ( 'primary' === $args->theme_location ) {
		ob_start();
		wp_loginout( home_url() );
		$loginoutlink = ob_get_contents();
		ob_end_clean();
		if ( ! is_user_logged_in() ) {
			$items .= '<li class="my-login menu-item"><a rel="nofollow" href="/login/">Login</a></li>';
		} else {
			$items .= '<li class="my-login menu-item">' . $loginoutlink . '</li>';
		}
		return $items;
	} else {
		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'lsx_add_login_logout_register_menu', 199, 2 );

/**
 * Redirect user after login or redirect
 *
 * @return void
 */
function lsx_login_redirect() {
	return home_url( 'my-plan' );
}
add_filter( 'woocommerce_login_redirect', 'lsx_login_redirect' );

/**
 * Undocumented function
 *
 * @return void
 */
function lsx_health_plan() {
	return \lsx_health_plan\classes\Core::get_instance();
}
lsx_health_plan();

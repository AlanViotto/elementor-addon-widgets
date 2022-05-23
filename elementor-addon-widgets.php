<?php
/*
 * Plugin Name: Elementor Addons & Templates - Sizzify Lite
 * Plugin URI: https://themeisle.com/
 * Description: Adds new Addons & Widgets that are specifically designed to be used in conjunction with the Elementor Page Builder.
 * Version: 1.4.0
 * Author: ThemeIsle | Fixed by silvaalanchristian
 * Author URI: https://github.com/silvaalanchristian
 * Requires at least:   5.9.3
 * Tested up to:        4.9
 *
 * Requires License: no
 * WordPress Available: yes
 */

/* Do not access this file directly */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
Constants
------------------------------------------ */

/* Set plugin version constant. */
define( 'EA_VERSION', '1.3.2' );

/* Set constant path to the plugin directory. */
define( 'EA_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/* Set the constant path to the plugin directory URI. */
define( 'EA_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

define( 'EA_PLUGIN_NAME', 'elementor_addon_widgets' );

define( 'SIZZIFY_UPSELL_LINK', 'https://themeisle.com/plugins/sizzify-elementor-addons-templates' );


/* ElemenTemplater Class */
require_once( EA_PATH . 'eaw-class.php' );

/**
 * Query WooCommerce activation
 */
if ( ! function_exists( 'eaw_is_woocommerce_active' ) ) {

	/**
	 * @return bool
	 * @deprecated use `class_exists( 'woocommerce' )`
	 */
	function eaw_is_woocommerce_active() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.0.0
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function eaw_do_shortcode( $tag, array $atts = array(), $content = null ) {

	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/* Require vendor file. */
$vendor_file = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
if ( is_readable( $vendor_file ) ) {
	require_once $vendor_file;

}

/**
 * Register SDK.
 *
 * @param $products
 *
 * @return array
 */
function elementor_addon_widgets_register_sdk( $products ) {
	$products[] = __FILE__;
	return $products;
}

add_filter( 'themeisle_sdk_products', 'elementor_addon_widgets_register_sdk', 10, 1 );

/**
 * Delete user meta 'sizzify_ignore_neve_notice' on plugin uninstall
 */
function elementor_addon_widgets_uninstall() {
	$all_users = get_users(
		array(
			'meta_key'   => 'sizzify_ignore_neve_notice',
			'meta_value' => 'true',
		)
	);
	foreach ( $all_users as $user ) {
		delete_user_meta( $user->ID, 'sizzify_ignore_neve_notice' );
	}
}
register_uninstall_hook( __FILE__, 'elementor_addon_widgets_uninstall' );


add_filter( EA_PLUGIN_NAME . '_enqueue_recommend', 'elementor_addon_widgets_upsell_plugins', 10, 2 );
/**
 * Validates the correct screen on which the assets for upsell should be loaded.
 */
function elementor_addon_widgets_upsell_plugins( $return, $screen_id ) {
	return $screen_id === 'toplevel_page_sizzify-admin';
}

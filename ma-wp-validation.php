<?php

/**
 * Plugin Name: ma-wp-validation
 * Plugin URI: https://github.com/your-username/ma-wp-validation
 * Description: A powerful and flexible validation plugin for WordPress that enhances request handling and validation for form submissions and REST API requests.
 * Version: 1.0.0
 * Author: Mehdi Ardeshir
 * Author URI: https://github.com/mehdi0121
 * License: GPL-3.0+
 * License URI: https://opensource.org/licenses/GPL-3.0
 * Text Domain: ma-wp-validation
 */

add_action('init', 'register_my_custom_classes');

function register_my_custom_classes() {
	if (!class_exists('MA_REST_Request')) {
		require_once plugin_dir_path(__FILE__) . '/inc/class-ma-rest-request.php';
	}
}

if (!function_exists('ma_rest_request')) {
	/**
	 * @param WP_REST_Request $request
	 *
	 * @return MA_REST_Request
	 */
	function ma_rest_request(WP_REST_Request $request) {
		return new MA_REST_Request($request);
	}
}

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
	function ma_rest_request(WP_REST_Request $request) {
		return new MA_REST_Request($request);
	}
}

add_action("rest_api_init", "test_api_register");

function test_api_register() {
	register_rest_route(
		"next/v1",
		"test",
		[
			"methods" => "post",
			"callback" => "api_callback"
		]
	);
}

function api_callback(WP_REST_Request $request) {
	$custom_request = ma_rest_request($request);

	var_dump($custom_request->get_params());
	var_dump($request->get_params());

	// قوانین ولیدیشن
	$rules = [
		'email' => 'required|email',
		'username' => 'required|min:5|max:15|unique:wp_users,user_login'
	];

	if (!$custom_request->validate($rules)) {
		return new WP_REST_Response([
			'status' => 'error',
			'errors' => $custom_request->getErrors()
		], 400);
	}

	return new WP_REST_Response(['status' => 'success']);
}

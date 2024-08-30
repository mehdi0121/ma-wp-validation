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



add_action("rest_api_init","test_api_register");



function test_api_register(){

	register_rest_route(
		"next/v1",
		"test",
		[
			"methods"=>"post",
			"callback"=>"api_callback"
		]
	);


}


function api_callback(WP_REST_Request $request){

	return  $request->sanitize_params();
}
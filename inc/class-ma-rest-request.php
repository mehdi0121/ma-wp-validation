<?php
include "class-ma-wp-validation.php";

class MA_REST_Request extends WP_REST_Request {
	protected MA_WP_Validation $validation;

	public function __construct(WP_REST_Request $request = null) {
		parent::__construct($request->get_method(), $request->get_route(), $request->get_attributes());

			$this->set_url_params($request->get_url_params());
			$this->set_query_params($request->get_query_params());
			$this->set_body_params($request->get_body_params());
			$this->set_default_params($request->get_default_params());
			$this->set_headers($request->get_headers());
			$this->set_attributes($request->get_attributes());
			$this->set_file_params($request->get_file_params());

		$this->validation = new MA_WP_Validation();
	}

	public function validate($rules) {
		$params = $this->get_params();
		$files = $this->get_file_params();

		return $this->validation->validation($rules, $params, $files);
	}

	public function getErrors() {
		return $this->validation->getErrors();
	}

	public function input($key) {
		if ($this->has_param($key)) {
			$param = $this->get_param($key);

			if (is_email($param)) {
				$param = sanitize_email($param);
			} elseif (filter_var($param, FILTER_VALIDATE_URL)) {
				$param = esc_url_raw($param);
			} elseif (is_string($param)) {
				$param = sanitize_text_field($param);
			} elseif (is_array($param) || is_object($param)) {
				$param = array_map('sanitize_text_field', (array)$param);
			}

			return trim($param);
		}
		return null;
	}
}

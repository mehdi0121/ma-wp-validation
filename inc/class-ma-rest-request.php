<?php

class MA_REST_Request extends WP_REST_Request {
	protected $validation;

	public function __construct(WP_REST_Request $request = null) {
		parent::__construct();

		if ($request) {
			$this->set_url_params($request->get_url_params());
			$this->set_query_params($request->get_query_params());
			$this->set_body_params($request->get_body_params());
			$this->set_default_params($request->get_default_params());
			$this->set_headers($request->get_headers());
			$this->set_attributes($request->get_attributes());
		}

		$this->validation = new MA_WP_Validation();
	}

	public function set_url_params($params) {
		$this->url_params = $params;
	}

	public function set_query_params($params) {
		$this->query_params = $params;
	}

	public function set_body_params($params) {
		$this->body_params = $params;
	}

	public function set_default_params($params) {
		$this->default_params = $params;
	}

	public function set_headers($headers, $override = true) {
		$this->headers = $headers;
	}

	public function set_attributes($attributes) {
		$this->attributes = $attributes;
	}

	public function get_params() {
		return array_merge(
			$this->url_params ?? [],
			$this->query_params ?? [],
			$this->body_params ?? [],
			$this->default_params ?? []
		);
	}

	public function validate($rules) {
		$params = $this->get_params();
		return $this->validation->validation($rules, $params);
	}

	public function getErrors() {
		return $this->validation->getErrors();
	}
}

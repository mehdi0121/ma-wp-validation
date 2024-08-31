<?php

class MA_WP_Validation {
	public $data;
	public array $errors = [];

	public function __construct() {



	}

	/**
	 * @param $rules
	 * @param $data
	 *
	 * @return bool
	 */
	public function validation($rules, $data) {
		$this->data = $data;
		$valid = true;

		foreach ($rules as $item => $ruleSet) {
			$ruleSet = explode("|", $ruleSet);
			$value = $data[ $item ] ?? null;

			foreach ($ruleSet as $rule) {
				$parameters = explode(":", $rule);
				$rule = $parameters[0];
				$parameter = isset($parameters[1]) ? explode(",", $parameters[1]) : [];

				$method = ucfirst($rule);

				if (method_exists($this, $method)) {
					if ($this->{$method}($value, $item, ...$parameter) === false) {
						$valid = false;
						break;
					}
				}
			}
		}

		return $valid;
	}

	/**
	 * @param $value
	 * @param $item
	 *
	 * @return bool
	 */
	public function required($value, $item) {
		if (empty($value)) {
			$this->errors[] = sprintf(__('The %s field is required.', 'ma-wp-validation'), $item);
			return false;
		}

		return true;
	}

	public function email($value, $item) {
		if (!is_email($value)) {
			$this->errors[] = sprintf(__('The email you entered is not valid.', 'ma-wp-validation'), $item);
			return false;
		}

		return true;
	}

	public function min($value, $item, $min) {
		if (strlen($value) < $min) {
			$this->errors[] = sprintf(__('The minimum length of %s field is %s.', 'ma-wp-validation'), $item, $min);
			return false;
		}

		return true;
	}




	public function max($value, $item, $max): bool {
		if (strlen($value) > $max) {
			$this->errors[] = sprintf(__('The maximum length of %s field is %s.', 'ma-wp-validation'), $item, $max);
			return false;
		}

		return true;
	}

	public function unique($value, $item, $table, $column = 'id'): bool {
		global $wpdb;

		$exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE $column = %s", $value));
		if ($exists) {
			$this->errors[] = sprintf(__('The %s value already exists in %s.', 'ma-wp-validation'), $item, $table);
			return false;
		}

		return true;
	}

	public function getErrors() {
		return $this->errors;
	}
}

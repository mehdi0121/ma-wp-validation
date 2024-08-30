<?php


class MA_WP_Validation {
		public $data;
		public $error=[];

	public function __construct() {



	}


	public function validation( $rule,$data ) {
		$this->data=$data;
		$valid=true;

		$explode_rules=explode("|",$rule);

		foreach ($explode_rules as $item=>$rule){
			$pos=strpos(":",$rule);
			if ($pos){
				$parameter=substr($rule,$pos+1);
				$rule=substr($rule,0,$pos);
			}else{
				$parameter="";
			}
			$value=isset($data[$item]) ? $data[$item]:null;
			$method=ucfirst($rule);

			if (method_exists($this,$method)){
				if ($this->{$method}($value,$item,$parameter)===false){
					$valid=false;
						break;
				}
			}
		}

		return $valid;

	}


	public function required($item, $value, $parameter) {

		if (empty($value) ) {
			$this->error[] = sprintf(__('The %s field is required.', 'ma-wp-validation'), $item);
		}

		return true;
	}

	public function email($item, $value, $parameter) {
		if (!is_email($value) ) {
			$this->error[] = sprintf(__('The email you entered is not valid.', 'ma-wp-validation'), $item);
		}

		return true;
	}

	public function min($item, $value, $parameter) {
		if ($this->required($item, $value, $parameter)){
			if (strlen($value)<=$parameter){
				$this->error[] = sprintf(__('The minimum  of  %s field is %s.', 'ma-wp-validation'), $item,$parameter);
			}
		}

		return true;
	}


	public function max($item, $value, $parameter) {
		if ($this->required($item, $value, $parameter)){
			if (strlen($value)>=$parameter){
				$this->error[] = sprintf(__('The maximum  of  %s field is %s.', 'ma-wp-validation'), $item,$parameter);
			}
		}

		return true;

	}

}
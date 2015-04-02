<?php
/**
 * Special Class for handling User Errors
 */
class UserError extends Exception {

	public function __construct($message, $code = 400) {
		parent::__construct($message, $code);
	}

	public function get_json() {
		return  '{"message": "'.$this->message.'", "code": "'.$this->code.'"}';
	}
}
?>
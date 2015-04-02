<?php
/**
 * Eine spezielle Exception, die bei einem Fehler auf der Seite geworfen wird.
 */
class InternalError extends Exception {
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
}
?>
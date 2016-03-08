<?php namespace amekusa\philes; main::required;

class RecoverableError extends \ErrorException {

	/**
	 * Explicitly triggers a PHP error
	 * @param boolean $ShowsStackTrace *(optional)* Whether to include the stack trace into the error message
	 */
	public function trigger($ShowsStackTrace = false) {
		$msg = $this->getMessage();
		if ($ShowsStackTrace) $msg .= "\nStack Trace:\n{$this->getTraceAsString()}";
		$code = E_USER_WARNING;
		switch ($this->getCode()) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$code = E_USER_NOTICE;
				break;
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				$code = E_USER_DEPRECATED;
				break;
		}
		trigger_error($msg, $code);
	}
}

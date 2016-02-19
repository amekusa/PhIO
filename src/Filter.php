<?php namespace amekusa\philes;

/**
 * @author amekusa <post@amekusa.com>
 */
class Filter {
	protected
		$pattern;

	/**
	 * @param string $Pattern
	 */
	public function __construct($Pattern) {
		$this->pattern = $Pattern;
	}

	/**
	 * @param string|File $File
	 * @return boolean
	 */
	public function matches($File) {
		return fnmatch($this->pattern, (string) $File);
	}
}

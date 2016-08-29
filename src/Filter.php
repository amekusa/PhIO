<?php namespace amekusa\phio; main::required;

/**
 * @author amekusa <post@amekusa.com>
 */
class Filter {
	protected
		$pattern;

	/**
	 * Alias of {@link __construct()}
	 * @param string $Pattern
	 * @return Filter
	 */
	public static function create($Pattern) {
		return new static($Pattern);
	}

	/**
	 * Creates a {@link Filter} object from a filtering pattern
	 * @param string $Pattern
	 */
	public function __construct($Pattern) {
		$this->pattern = $Pattern;
	}

	/**
	 * Returns whether a file matches with this filter
	 * @param string|File $File File path or {@link File} object
	 * @return boolean
	 */
	public function matches($File) {
		return fnmatch($this->pattern, (string) $File);
	}
}

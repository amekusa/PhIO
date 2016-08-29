<?php namespace amekusa\phio; main::required;

/**
 * @author amekusa <post@amekusa.com>
 */
class RegexFilter extends Filter {

	public function matches($File) {
		return preg_match($this->pattern, (string) $File);
	}
}

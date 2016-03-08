<?php namespace amekusa\philes; main::required;

/**
 * File permissions abstraction
 * @author amekusa <post@amekusa.com>
 */
class Perms {
	protected
		$mode;

	/**
	 * @param integer|string $Mode
	 */
	public function __construct($Mode = 0777) {
		$this->mode = is_int($Mode) ? $Mode : octdec((string) $Mode);
	}

	public function isOwnerReadable() {
		return $this->getMode() & 0x0100;
	}

	public function isOwnerWritable() {
		return $this->getMode() & 0x0080;
	}

	public function isOwnerExecutable() {
		return $this->getMode() & 0x0040;
	}

	public function isGroupReadable() {
		return $this->getMode() & 0x0020;
	}

	public function isGroupWritable() {
		return $this->getMode() & 0x0010;
	}

	public function isGroupExecutable() {
		return $this->getMode() & 0x0008;
	}

	public function isUserReadable() {
		return $this->getMode() & 0x0004;
	}

	public function isUserWritable() {
		return $this->getMode() & 0x0002;
	}

	public function isUserExecutable() {
		return $this->getMode() & 0x0001;
	}

	/**
	 * @return integer
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * @param integer $Mode File mode in octal number format
	 */
	public function setMode($Mode) {
		$this->mode = $Mode;
		return $this;
	}
}
<?php namespace amekusa\philes;

/**
 * File abstraction
 * @author amekusa <post@amekusa.com>
 */
abstract class File {
	protected
		$path,
		$io,
		$perms,
		$isExclusive;

	/**
	 * Creates a proper File object from a path
	 *
	 * If the path is:
	 *
	 * + a directory, creates a {@link Directory} object.
	 * + a file, creates a {@link Resource} object.
	 *
	 * @example Passing a directory path, creates a {@link Directory} object
	 * ```php
	 * use amekusa\philes\Directory;
	 *
	 * $dir = File::create(__DIR__);
	 *
	 * echo 'Is $dir a directory? ';
	 * echo $dir instanceof Directory ? 'Yes' : 'No';
	 * ```
	 * @example Passing a resource path, creates a {@link Resource} object
	 * ```php
	 * use amekusa\philes\Resource;
	 *
	 * $res = File::create(__FILE__);
	 *
	 * echo 'Is $res a resource? ';
	 * echo $res instanceof Resource ? 'Yes' : 'No';
	 * ```
	 * @param string $Path
	 * @return File
	 */
	public static function create($Path) {
		$r = is_dir($Path) ? new Directory($Path) : new Resource($Path);
		return $r;
	}

	/**
	 * Creates a {@link File} object from a path
	 * @param string $Path File path
	 */
	public function __construct($Path) {
		$this->path = $Path;
		$this->normalizePath();
	}

	public function __toString() {
		return $this->path;
	}

	public function isExclusive() {
		return $this->isExclusive;
	}

	public function isOpened() {
		return isset($this->io);
	}

	/**
	 * @return boolean
	 */
	public function exists() {
		return file_exists($this->path);
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return basename($this->path);
	}

	/**
	 * @return Perms
	 */
	public function getPerms() {
		if (!$this->perms) {
			clearstatcache();
			$mode = @fileperms($this->path);
			$this->perms = $mode ? new Perms(decoct($mode & 0777)) : new Perms();
		}
		return $this->perms;
	}

	/**
	 * @param string $Format
	 * @return integer|string
	 */
	public function modifiedAt($Format = '') {
		$time = filemtime($this->path);
		return $Format ? date($Format, $time) : $time;
	}

	/**
	 * @param boolean $IsExclusive
	 * @return File
	 */
	public function beExclusive($IsExclusive = true) {
		$this->isExclusive = $IsExclusive;
		return $this;
	}

	protected function normalizePath() {
		$this->path = rtrim($this->path, '/');
	}

	public function open() {
		if ($this->isOpened()) {
			// TODO Warn or throw exception
			return false;
		}
		$this->_open();
		if ($this->isExclusive()) flock($this->io, LOCK_EX);
		return true;
	}

	protected abstract function _open();

	public function close() {
		if (!$this->isOpened()) {
			// TODO Warn or throw exception
			return false;
		}
		if ($this->isExclusive()) flock($this->io, LOCK_UN);
		$this->_close();
		$this->io = null;
		return true;
	}

	protected abstract function _close();

	/**
	 * @param string|Directory $Destination
	 * @return boolean True on success, False on failure
	 */
	public function moveTo($Destination) {
		return rename($this->path, "{$Destination}/{$this->getName()}");
	}

	public function remove() {
		return unlink($this->path);
	}
}

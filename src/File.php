<?php namespace amekusa\philes; main::required;

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
	 * Creates a proper object of {@link File} subclass from a path
	 *
	 * If the path is:
	 *
	 * + a directory, creates a {@link Directory} object.
	 * + a file, creates a {@link Resource} object.
	 *
	 * @example Passing an existing directory path, creates a {@link Directory} object
	 * ```php
	 * use amekusa\philes\Directory;
	 *
	 * $dir = File::create(__DIR__);
	 *
	 * echo 'Is $dir a directory? - ';
	 * echo $dir instanceof Directory ? 'Yes.' : 'No.';
	 * ```
	 * @example Passing an existing regular file path or non-existent path, creates a {@link Resource} object
	 * ```php
	 * use amekusa\philes\Resource;
	 *
	 * $res = File::create(__FILE__);
	 *
	 * echo 'Is $res a regular file? - ';
	 * echo $res instanceof Resource ? 'Yes.' : 'No.';
	 * ```
	 * @param string $Path
	 * @return File
	 */
	public static function create($Path) {
		$r = is_dir($Path) ? new Directory($Path) : new Resource($Path);
		return $r;
	}

	/**
	 * Returns a {@link File} instance associated with a specific file path
	 *
	 * The operation is the same as {@link File}`::create($Path)`
	 * except for the returned object is cached in {@link FilePool}.
	 *
	 * @example Cache Demonstration
	 * ```php
	 * $X1 = File::create(__FILE__);         // Not cached
	 * $Y1 = File::create(__FILE__);         // Not cached
	 *
	 * $X2 = File::instance(__FILE__);       // Not cached
	 * $Y2 = File::instance(__FILE__);       // Cached
	 *
	 * $X3 = File::instance(__FILE__, true); // Not cached
	 * $Y3 = File::instance(__FILE__, true); // Not cached
	 * $Z3 = File::instance(__FILE__);       // Cached
	 *
	 * echo 'Are $X1 and $Y1 identical? - ';
	 * echo $X1 === $Y1 ? 'Yes.' : 'No.';
	 * echo "\n";
	 *
	 * echo 'Are $X2 and $Y2 identical? - ';
	 * echo $X2 === $Y2 ? 'Yes.' : 'No.';
	 * echo "\n";
	 *
	 * echo 'Are $X3 and $Y3 identical? - ';
	 * echo $X3 === $Y3 ? 'Yes.' : 'No.';
	 * echo "\n";
	 *
	 * echo 'Are $Y3 and $Z3 identical? - ';
	 * echo $Y3 === $Z3 ? 'Yes.' : 'No.';
	 * ```
	 * @param string $Path The path of a file
	 * @param boolean $ForceNew If `true`, always returns a newly created instance of {@link File}
	 * @return File
	 */
	public static function instance($Path, $ForceNew = false) {
		return FilePool::instance()->get($Path, $ForceNew);
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

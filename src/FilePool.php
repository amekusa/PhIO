<?php namespace amekusa\philes; main::required;

/**
 * @author amekusa <post@amekusa.com>
 */
class FilePool {
	protected static $instance;
	protected $pool = array ();

	/**
	 * Returns the instance of This class
	 * @return FilePool
	 */
	public static function instance() {
		if (!isset(static::$instance)) static::$instance = new static();
		return static::$instance;
	}

	protected function __construct() {
	}

	/**
	 * @param string $Path The path of a File object to return
	 * @param boolean $ForceNew *(optional)* If `true`, always returns a newly created object
	 * @return File
	 */
	public function get($Path, $ForceNew = false) {
		if ($ForceNew || !isset($this->pool[$Path]) || !$this->pool[$Path]) {
			$r = File::create($Path);
			$this->pool[$Path] = $r;
			return $r;
		}
		return $this->pool[$Path];
	}

	public function clear() {
		$this->pool = array ();
	}
}

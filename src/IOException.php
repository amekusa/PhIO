<?php namespace amekusa\philes; main::required;

/**
 * @author amekusa <post@amekusa.com>
 */
class IOException extends \RuntimeException {
	protected $ioFile;

	/**
	 * @ignore
	 * @param string $Msg
	 * @param integer $Code
	 * @param \Exception $Previous
	 * @return IOException
	 */
	public static function create($Msg = null, $Code = null, $Previous = null) {
		return new static($Msg, $Code, $Previous);
	}

	/**
	 * Returns a {@link File} object that the exception occurred on its I/O operation
	 * @return File
	 */
	public function getIOFile() {
		return $this->ioFile;
	}

	/**
	 * Sets a {@link File} object that the exception occurred on its I/O operation
	 * @param File $IOFile
	 * @return IOException This
	 */
	public function setIOFile(File $IOFile) {
		$this->ioFile = $IOFile;
		return $this;
	}
}

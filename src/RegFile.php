<?php namespace amekusa\phio; main::required;

/**
 * Regular file abstraction
 * @author amekusa <post@amekusa.com>
 */
class RegFile extends File {
	protected
		$mode;

	/**
	 * @return string
	 */
	public function getContent() {
		if ($jit = !$this->isOpened()) $this->open();
		$r = fread($this->io, $this->getSize());
		if ($jit) $this->close();
		return $r;
	}

	public function getSize() {
		return filesize($this->path);
	}

	/**
	 * @param string $Mode
	 * @return RegFile The object itself
	 */
	public function setMode($Mode) {
		$this->mode = $Mode;
		return $this;
	}

	protected function _open() {
		try {
			$this->io = fopen($this->path, $this->mode ?: 'r');
		} catch (ErrorException $E) {
			throw IOException::create("Couldn't open the file: $this")->setIOFile($this);
		}
	}

	protected function _close() {
		if (!fclose($this->io))
			throw IOException::create("Couldn't close the file: $this")->setIOFile($this);
		return true;
	}
}

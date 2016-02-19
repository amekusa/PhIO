<?php namespace amekusa\philes;

/**
 * Resource abstraction
 * @author amekusa <post@amekusa.com>
 */
class Resource extends File {
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
	 * @return Resource This
	 */
	public function setMode($Mode) {
		$this->mode = $Mode;
		return $this;
	}

	protected function _open() {
		$this->io = @fopen($this->path, $this->mode ?: 'r');
	}

	protected function _close() {
		return @fclose($this->io);
	}
}

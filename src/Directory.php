<?php namespace amekusa\philes; main::required;

/**
 * Directory abstraction
 * @author amekusa <post@amekusa.com>
 */
class Directory extends File implements \IteratorAggregate {
	protected
		$filters;

	public function getIterator() {
		return new \ArrayIterator($this->getFiles());
	}

	public function getFiles() {
		if ($jit = !$this->isOpened()) $this->open();
		$r = array ();
		while (($file = readdir($this->io)) !== false) {
			if ($file == '.' || $file == '..') continue;
			if (!$this->filter($file)) continue;
			$r[] = File::create("{$this->path}/{$file}");
		}
		if ($jit) $this->close();
		return $r;
	}

	/**
	 * @param array $Filters
	 * @return Directory This
	 */
	public function setFilters($Filters) {
		$this->filters = $Filters;
		return $this;
	}

	/**
	 * @param Filter $Filter
	 * @return Directory This
	 */
	public function addFilter(Filter $Filter) {
		$this->filters[] = $Filter;
		return $this;
	}

	/**
	 * @param string $Filename
	 * @return boolean
	 */
	protected function filter($Filename) {
		if (!$this->filters) return true;
		foreach ($this->filters as $iFilter) {
			if ($iFilter->matches($Filename)) return true;
		}
		return false;
	}

	protected function _open() {
		$this->io = @opendir($this->path);
	}

	protected function _close() {
		return @closedir($this->io);
	}
}

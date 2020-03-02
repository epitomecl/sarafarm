<?php

namespace modules;

use \Exception as Exception;

/**
*
*/
class Land {
	private $mysqli;
	private $area;
	private $location;
	private $ownership;
	private $periodOfOwnership;

	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
}
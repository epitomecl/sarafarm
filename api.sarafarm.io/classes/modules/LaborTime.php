<?php

namespace modules;

use \Exception as Exception;

/**
*
*/
class LaborTime {
	private $mysqli;
	private $ownLaborTime;
	private $hiredLaborTime;

	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
}
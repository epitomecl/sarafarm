<?php

namespace modules;

use \Exception as Exception;

/**
* var $crop = ICrops::RADISH;
*/
interface ICrops
{
    const RADISH = 1;
    const PEPPER = 2;
	const ONION = 3;
	const GARLIC = 4;
	const CABBAGE = 5;
}

/**
*
*/
class Crops {
	private $mysqli;
	private $type;
	private $amountLastYear;
	private $amountThisYear;
	private $priceLastYear;
	private $seasonStartDate;
	private $seasonEndDate;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
}
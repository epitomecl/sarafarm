<?php

namespace modules;

use \Exception as Exception;

/**
* If user session is alive, vegetarian data will be managed.
* A POST request will update food data.
*/
class Vegetarian {
	private $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function doPost($foodCode, $column, $checked) {

		if (strlen($column) > 0 && $foodCode > 0) {
			if (in_array($column, array("vegan", "lacto_vegan", "ovo_vegan", "pescatarian"))) {
				$sql = "UPDATE food SET %s='%d' WHERE food_code=%d";
				$sql = sprintf($sql, $column, $checked, $foodCode);

				if ($this->mysqli->query($sql) === false) {
					throw new Exception(sprintf("%s, %s", get_class($this), $this->mysqli->error), 507);
				}
			}
		}
		
		echo json_encode(array("foodCode" => $foodCode), JSON_UNESCAPED_UNICODE);
	}
}
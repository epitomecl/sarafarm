<?php

namespace modules;

use \Exception as Exception;

/**
* If user session is alive, vegetarian data will be managed.
* A POST request will update food data.
*/
class FoodInfo {
	private $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function doGet($foodCode) {
		$mysqli = $this->mysqli;	
		
		$data = array();		
		$sql = "SELECT food_code, main_food_description, food_name_kr, image, ";
		$sql .= "energy as calorie, protein, omega3, ";
		$sql .= "IF (omega3 >= 0.25, 1, 0) AS star_omega, ";
		$sql .= "IF (protein > 20, 1, 0) AS star_protein, ";
		$sql .= "IF (food_mark = 'korean', 1, 0) AS star_taegeuk, ";
		$sql .= "IF (food_mark = 'rgo', 1, 0) AS star_rgo ";
		$sql .= "FROM food ";
		$sql .= sprintf("WHERE food_code =%d;", $foodCode);
		
		if ($result = $mysqli->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				if (empty($row["image"])) {
					$row["image"] = "img/food/00000000.jpg";
				}
				
				foreach ($row as $key => $value) {
					if (strcmp($key, "omega3") == 0) {
						$row[$key] = sprintf("%.2f", $row[$key]);						
					} else if (strcmp($key, "calorie") == 0) {
						$row[$key] = sprintf("%.1f", $row[$key]);
					} else if (strcmp($key, "protein") == 0) {
						$row[$key] = sprintf("%.1f", $row[$key]);
					}
				}
				
				array_push($data, $row);
			}
		}
		
		$obj = new \stdClass();
		$obj->data = $data;
		$obj->module = (new \ReflectionClass($this))->getShortName();
		
		echo json_encode($obj, JSON_UNESCAPED_UNICODE);
	}
}
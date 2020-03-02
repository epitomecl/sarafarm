<?php

namespace modules;

use \Exception as Exception;

/**
* If user session is alive, vegetarian data will be managed.
* A POST request will update food data.
*/
class SummaryChart {
	private $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function doGet($arrBreakfast, $arrLunch, $arrDinner, $arrSnack) {
		$mysqli = $this->mysqli;	
		
		$obj = new \stdClass();
		$obj->data = array(
			$this->getFoodInfo($mysqli, array_merge($arrBreakfast, $arrLunch, $arrDinner, $arrSnack)),
			array(
			"breakfast" => $this->getData($mysqli, $arrBreakfast),
			"lunch" => $this->getData($mysqli, $arrLunch),
			"dinner" => $this->getData($mysqli, $arrDinner),
			"snack" => $this->getData($mysqli, $arrSnack),
			"total" => $this->getData($mysqli, array_merge($arrBreakfast, $arrLunch, $arrDinner, $arrSnack))
			));
		$obj->module = (new \ReflectionClass($this))->getShortName();
		
		echo json_encode($obj, JSON_UNESCAPED_UNICODE);
	}
	
	private function getData($mysqli, $arrFoodCode) {
		$data = array();		
		$union = array();
		
		foreach ($arrFoodCode as $foodCode) {
			if ($foodCode > 0) {
				$sql = "(SELECT ";
				$sql .= "energy, protein, carbohydrate, fat, saturated, ";
				$sql .= "cholestorol, sugar, fiber, vitamin_d, vitamin_b12, ";
				$sql .= "calcium, potassium, sodium, ";
				$sql .= "lipid_18_3, lipid_20_5, lipid_22_6, omega3 ";
				$sql .= "FROM food ";
				$sql .= sprintf("WHERE food_code = %d)", $foodCode);
				array_push($union, $sql);
			}
		}
		
		$sql = "SELECT ";
		$sql .= "SUM(energy) AS calorie, SUM(protein) AS protein, SUM(carbohydrate) AS carbohydrate, SUM(fat) AS fat, SUM(saturated) AS saturated, ";
		$sql .= "SUM(cholestorol) AS cholestorol, SUM(sugar) AS sugar, SUM(fiber) AS fiber, SUM(vitamin_d) AS vitamin_d, SUM(vitamin_b12) AS vitamin_b12, ";
		$sql .= "SUM(calcium) AS calcium, SUM(potassium) AS potassium, SUM(sodium) AS sodium, ";
		$sql .= "SUM(lipid_18_3) AS lipid_18_3, SUM(lipid_20_5) AS lipid_20_5, SUM(lipid_22_6) AS lipid_22_6, SUM(omega3) AS omega3 ";
		$sql .= "FROM (";
		$sql .= implode(" UNION ALL ", $union);
		$sql .= ") AS data;";
		
		if ($result = $mysqli->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				foreach ($row as $key => $value) {
					if (strcmp($key, "omega3") == 0) {
						$row[$key] = sprintf("%.2f", $row[$key]);						
					} else {
						$row[$key] = sprintf("%.1f", $row[$key]);
					}
				}
				array_push($data, $row);
			}
		}
			
		return $data;
	}
	
	public function getFoodInfo($mysqli, $arrFoodCode) {
		$data = array();		
		$sql = "SELECT food_code, main_food_description, food_name_kr, image, ";
		$sql .= "energy as calorie, protein, omega3, ";
		$sql .= "IF (omega3 >= 0.25, 1, 0) AS star_omega, ";
		$sql .= "IF (protein > 20, 1, 0) AS star_protein, ";
		$sql .= "IF (food_mark = 'korean', 1, 0) AS star_taegeuk, ";
		$sql .= "IF (food_mark = 'rgo', 1, 0) AS star_rgo ";
		$sql .= "FROM food ";
		$sql .= sprintf("WHERE food_code IN (%s) ", implode(",", $arrFoodCode));
		$sql .= "ORDER BY main_food_description, food_name_kr;";
		
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
		return $data;
	}
}
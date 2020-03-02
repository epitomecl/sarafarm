<?php

namespace modules;

use \Exception as Exception;

/**
* If user session is alive, vegetarian data will be managed.
* A POST request will update food data.
*/
class FoodMenu {
	private $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function doGet($page, $itemsPerPage, $dialog) {
		$mysqli = $this->mysqli;	
		
		$where = "";
		
		switch ($dialog) {
			// case "meal":
				// $where = "WHERE meal_category = 1 ";
				// break;
			case "snack":
				$where = "WHERE meal_category IN (2, 3) ";
				break;
		}
		
		$total = 0;
		$sql = "SELECT COUNT(food_id) AS total FROM food ";
		$sql .= $where;
		
		if ($result = $mysqli->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				$total = intval($row["total"]);
			}	
		}
	
		$itemsPerPage = ($itemsPerPage == 0) ? 25 : $itemsPerPage;
		$page = ($page == 0) ? 1 : $page; 
		$pages = ceil($total / $itemsPerPage); 
		$start = ($page - 1) * $itemsPerPage;
	
		$data = array();		
		$sql = "SELECT food_code, main_food_description, food_name_kr, image, ";
		$sql .= "vegan, lacto_vegan, ovo_vegan, pescatarian ";
		$sql .= "FROM food ";
		$sql .= $where;
		$sql .= sprintf("LIMIT %d, %d", $start, $itemsPerPage);
		
		if ($result = $mysqli->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				if (empty($row["image"])) {
					$row["image"] = "img/food/00000000.jpg";
				}
				array_push($data, $row);
			}
		}
		
		$obj = new \stdClass();
		$obj->data = $data;
		$obj->dialog = $dialog;
		$obj->page = $page;
		$obj->pages = $pages;
		$obj->itemsPerPage = $itemsPerPage;
		$obj->module = (new \ReflectionClass($this))->getShortName();
		
		echo json_encode($obj, JSON_UNESCAPED_UNICODE);
	}
}
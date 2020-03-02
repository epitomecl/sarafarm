<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function insertAt($array = [], $item = [], $position = 0) {
    $previous_items = array_slice($array, 0, $position, true);
    $next_items     = array_slice($array, $position, NULL, true);
    return $previous_items + $item + $next_items;
}

function getParam($array, $param, $label = '') {
	if (array_key_exists($param, $array)) {
		if (strcmp($label, "array") == 0) {
			return $array[$param];
		} elseif (strcmp($label, "int") == 0) {
			return intval(trim($array[$param]));
		} elseif (strcmp($label, "double") == 0) {
			return doubleval(trim($array[$param]));
		} else {
			return strip_tags(stripslashes(trim($array[$param])));
		}
	}

	return null;
}

function getQueryString($array) {
	$items = array();
	$params = array_filter($array);
	
	foreach ($params as $key => $value) {
		array_push($items, sprintf("&%s=%s", $key, urlencode($value)));
	}
	
	return implode($items);
}

$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);

$total = 0;
$suggested = getParam($_GET, "suggested", "int");
$foodCode = getParam($_GET, "foodCode", "int");
$searchText = getParam($_GET, "searchText"); 
$foodCategoryCode = getParam($_GET, "foodCategoryCode", "int");
$snackCategoryCode = getParam($_GET, "snackCategoryCode", "int");
$itemsPerPage = getParam($_GET, "itemsPerPage", "int");
$page = getParam($_GET, "page", "int");
$queryString = getQueryString(array("itemsPerPage" => $itemsPerPage, "suggested" => $suggested, "foodCode" => $foodCode, "searchText" => $searchText, "foodCategoryCode" => $foodCategoryCode));
$foodCategoryOption = array();
$snackCategoryOption = array();

$tags = array();
$data = array();
$sql = "";
	
try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");

	$where = array();
	if ($suggested == 1) {
		array_push($where, sprintf("suggested=%d ", $suggested));
	}	
	if ($foodCode > 0) {
		array_push($where, sprintf("food_code=%d ", $foodCode));
	}
	if (strlen($searchText) > 0) {
		$text = "%".$mysqli->real_escape_string($searchText)."%";
		array_push($where, sprintf("(food_name_kr LIKE '%s' OR main_food_description LIKE '%s') ", $text, $text));		
	}
	if ($foodCategoryCode > 0) {
		array_push($where, sprintf("food_category_code=%d ", $foodCategoryCode));		
	}
	if ($snackCategoryCode > 0) {
		array_push($where, sprintf("meal_category=%d ", $snackCategoryCode));		
	}	
	if (count($where) > 0) {
		// add spaceholder item for AND
		array_unshift($where, "");
	}
	
	// tags
	$sql = "SELECT COLUMN_NAME, COLUMN_COMMENT, CHARACTER_MAXIMUM_LENGTH ";
	$sql .= "FROM information_schema.COLUMNS ";
	$sql .= "WHERE TABLE_SCHEMA = DATABASE() ";
	$sql .= "AND TABLE_NAME = '%s' ";
	$sql .= "ORDER BY ORDINAL_POSITION";
	$sql = sprintf($sql, "food");

	if ($result = $mysqli->query($sql)) {
		while ($field = $result->fetch_assoc()) {
			$tags[trim($field["COLUMN_NAME"])] = trim($field["COLUMN_COMMENT"]);
		}
	}

	// foodCategoryOption
	$sql = "SELECT DISTINCT food_category_code, food_category_description ";
	$sql .= "FROM food ";
	$sql .= "ORDER BY food_category_description;";

	if ($result = $mysqli->query($sql)) {
		while ($row = $result->fetch_assoc()) {
			$key = trim($row["food_category_code"]);
			$value = trim($row["food_category_description"]);
			$foodCategoryOption[$key] = $value;
		}	
	}

	// snackCategoryOption
	$snackCategoryOption = array("Main meal" => 1,  "Snacks" => 2, "Beverages" => 3);
	
	// total
	$sql = "SELECT COUNT(food_id) AS total FROM food ";
	$sql .= "WHERE 1 ";
	$sql .= implode("AND ", $where);
	
	if ($result = $mysqli->query($sql)) {
		while ($row = $result->fetch_assoc()) {
			$total = intval($row["total"]);
		}	
	}

	$itemsPerPage = ($itemsPerPage == 0) ? 50 : $itemsPerPage;
	$page = ($page == 0) ? 1 : $page; 
	$pages = ceil($total / $itemsPerPage); 
	$start = ($page - 1) * $itemsPerPage;	
	
	// data
	$sql = "SELECT food_id, food_code, main_food_description, food_name_kr, meal_category, image, ";
	$sql .= "vegan, lacto_vegan, ovo_vegan, pescatarian, food_category_description, ";
	$sql .= "energy, protein, carbohydrate, sugar, fiber, fat, saturated, cholestorol, vitamin_b12, vitamin_d, ";
	$sql .= "calcium, magnesium, iron, potassium, sodium, caffeine, alcohol, lipid_18_3, lipid_18_4, ";
	$sql .= "lipid_20_4, lipid_20_5, lipid_22_5, lipid_22_6, omega3 ";
	$sql .= "FROM food ";
	$sql .= "WHERE 1 ";
	$sql .= implode("AND ", $where);
	$sql .= sprintf("LIMIT %d, %d", $start, $itemsPerPage);

	if ($result = $mysqli->query($sql)) {
		while ($row = $result->fetch_assoc()) {
			if (empty($row["image"])) {
				$row["image"] = "/img/sarafarm.io.png";
			}

			array_push($data, $row);
		}	
	}
	
} catch (Exception $e) {
	$msg = $e->getMessage();
	$code = $e->getCode();
	http_response_code(($code == 0) ? 400 : $code);
	echo sprintf("Exception occurred in: %s", $msg);
} finally {
	$mysqli->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sarafarm</title>
  <link rel="shortcut icon" href="https://sarafarm.io/img/sarafarm.io.ico" />  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
  <style>
  
.jumbotron {
  text-align: center;
  padding-top: 4rem;
  padding-bottom: 4rem;
  text-shadow: 2px 2px #000;
  margin-bottom:0;
}  
.bg-cover {
  background-size: cover;
  color: white;
  background-position: center center;
  position: relative;
  z-index: -2;
  background-image: url('img/background_02.jpg');  
}
.overlay {
  background-color: #000;
  opacity: 0.3;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: -1;
}
.container_section {
    padding-top: 2rem;	
    padding-bottom: 2rem;
}
.status {
	color: red;
	font-weight: bold;
}
.cursor-pointer {
	cursor: pointer;
}
.bg-breakfast {
	background-color: #BBE1BB;
}
.bg-lunch {
	background-color: #8AFC8A;
}
.bg-dinner {
	background-color: #8198FF;
}
.bg-snack {
	background-color: #63BCBC;
}
.bg-total {
	background-color: #CEB08B;
}
.card-text {
	text-shadow: 1px 1px #CECECE;
}
  </style>
</head>
<body>

<div class="jumbotron bg-cover">
  <div class="overlay"></div>
  <div class="container">
  <h1>SaraEat</h1>
  <p>Real-time Interactive game and Personalised Diet Management</p>
  </div>
</div>
  
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<a class="navbar-brand" href="/index.php">
    <img src="img/saraeat.png" alt="Logo" style="width:40px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/gallery.php">Food Gallery</a>
      </li>	
      <li class="nav-item active">
        <a class="nav-link" href="/foodmenu.php">SaraGame</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/veggie.php">Vegan Weekly Plan</a>
      </li>	
      <li class="nav-item">
        <a class="nav-link" href="/aboutus.php">About us</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="/foodmenu_kr.php">한국어</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section">
	<div class="container-fluid">
		<div class="row pb-4">
			<div class="col-sm-12 text-justify">
				<h2>What do you eat for tomorrow?</h2>
				<p>
				This is a game which the user selects a menu and Sara calculates the total nutrients and calories in real time. 
				</p>
				<p>
				You can make multiple choices. 
				What would you like to have tomorrow?
				Here is the <a href="sarahelp.php">help video</a>.
				</p>
				<p>
					<h6>
					To make a food selection, click the SaraEat logo <img src="img/saraeat.png" alt="Logo" style="width:20px;">
					</h6>
				</p>
			</div>
		</div>
				
		<div class="row">
			<div class="col"><h4>1. Breakfast</h4></div>
		</div>
		<div class="row mb-3">
		<?php for ($index = 0; $index < 5; $index++): ?>
			<div class="col">
				<div class="card bg-success h-100 cursor-pointer" data-dialog="meal" data-card="breakfast_0<?php echo $index; ?>">
					<div class="card-body p-0 align-items-center d-flex justify-content-center">
						<img src="img/food/00000000.jpg" class="img-fluid rounded" data-foodcode="00000000" title="To make a food selection, click the SaraFarm logo.">
					</div>
					<div class="card-img-overlay overflow-auto">
						<p class="card-text">&#128070; Click this image!</p>
					</div>					
				</div>
			</div>
		<?php endfor; ?>
		</div>
		<div class="row">
			<div class="col"><h4>2. Lunch</h4></div>
		</div>
		<div class="row mb-3">
		<?php for ($index = 0; $index < 5; $index++): ?>
			<div class="col">
				<div class="card bg-success h-100 cursor-pointer" data-dialog="meal" data-card="lunch_0<?php echo $index; ?>">
					<div class="card-body p-0 align-items-center d-flex justify-content-center">
						<img src="img/food/00000000.jpg" class="img-fluid rounded" data-foodcode="00000000" title="To make a food selection, click the SaraFarm logo.">
					</div>
					<div class="card-img-overlay overflow-auto">
						<p class="card-text">&#128070; Click this image!</p>
					</div>					
				</div>
			</div>
		<?php endfor; ?>
		</div>		
		<div class="row">
			<div class="col"><h4>3. Dinner</h4></div>
		</div>
		<div class="row mb-3">
		<?php for ($index = 0; $index < 5; $index++): ?>
			<div class="col">
				<div class="card bg-success h-100 cursor-pointer" data-dialog="meal" data-card="dinner_0<?php echo $index; ?>">
					<div class="card-body p-0 align-items-center d-flex justify-content-center">
						<img src="img/food/00000000.jpg" class="img-fluid rounded" data-foodcode="00000000" title="To make a food selection, click the SaraFarm logo.">
					</div>
					<div class="card-img-overlay overflow-auto">
						<p class="card-text">&#128070; Click this image!</p>
					</div>					
				</div>
			</div>
		<?php endfor; ?>
		</div>		
		<div class="row">
			<div class="col"><h4>4. Snacks & beverage</h4></div>
		</div>
		<div class="row mb-3">
		<?php for ($index = 0; $index < 5; $index++): ?>
			<div class="col">
				<div class="card bg-success h-100 cursor-pointer" data-dialog="snack" data-card="snack_0<?php echo $index; ?>">
					<div class="card-body p-0 align-items-center d-flex justify-content-center">
						<img src="img/food/00000000.jpg" class="img-fluid rounded" data-foodcode="00000000" title="To make a food selection, click the SaraFarm logo.">
					</div>
					<div class="card-img-overlay overflow-auto">
						<p class="card-text">&#128070; Click this image!</p>
					</div>					
				</div>
			</div>
		<?php endfor; ?>
		</div>		
	</div>
</section>

<section class="container_section bg-light">
	<div class="container-fluid">
		<div class="row pb-4">
			<div class="col-sm-12">
				<button type="button" class="btn btn-lg btn-outline-success btn-block module mt-2 mb-2">Let Sara progress with the Summary chart.</button>
				<div class="viewSummeryFood pt-4">
					<div id="foodCards" class="card-columns collapse">
					</div>
				</div>
				<template id="itemSummeryFood">
					<div class="card bg-light">
						<div class="card-body text-center">
							<h6 class="card-title">${main_food_description}</h6>
							<div class="table-responsive p-0">
								<table class="table table-striped table-bordered">
									<thead>
										<th>Total Calorie</th>
										<th>Total protein</th>
										<th>Omega 3</th>
										<th>Sara’s star food</th>
									</thead>
									<tbody>
										<td>${calorie} kcal</td>
										<td>${protein} g</td>
										<td>${omega3} g</td>
										<td> ${star_rgo} ${star_omega} ${star_protein} ${star_taegeuk} </td>
									</tbody>
								</table>	
							</div>
						</div>
					</div>					
				</template>				
				<div class="viewSummeryChart"></div>
				<template id="itemSummeryChartBreakfast">
				<div class="row p-2 bg-breakfast">
					<div class="col-sm-2"><h4>Breakfast</h4></div>
					<div class="col-sm-10">
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<th>Total Calorie</th>
									<th>Total protein</th>
									<th>Total Carbohydrate</th>
									<th>Total fat</th>
									<th>Saturated fat</th>
									<th>Total sugars</th>
									<th>Cholestorol</th>
									<th>Vitamin D</th>
									<th>Vitamin B12</th>
									<th>Calcium</th>
									<th>Potassium</th>
									<th>Fiber, total dietary</th>
									<th>Total Sodium</th>
									<th>ALA 18:3</th>
									<th>EPA 20:5 n-3</th>
									<th>DHA 22:6 n-3</th>
									<th>Omega 3</th>
								</thead>
								<tbody>
									<td>${calorie} kcal</td>
									<td>${protein} g</td>
									<td>${carbohydrate} g</td>
									<td>${fat} g</td>
									<td>${saturated} g</td>
									<td>${sugar} g</td>
									<td>${cholestorol} mg</td>
									<td>${vitamin_d} mg</td>
									<td>${vitamin_b12} mg</td>
									<td>${calcium} mg</td>
									<td>${potassium} mg</td>
									<td>${fiber} g</td>
									<td>${sodium} mg</th>
									<td>${lipid_18_3} g</td>
									<td>${lipid_20_5} g</td>
									<td>${lipid_22_6} g</td>
									<td>${omega3} g</td>
								</tbody>
							</table>	
						</div>
					</div>
				</div>
				</template>
				<template id="itemSummeryChartLunch">
				<div class="row p-2 bg-lunch">
					<div class="col-sm-2"><h4>Lunch</h4></div>
					<div class="col-sm-10">
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<th>Total Calorie</th>
									<th>Total protein</th>
									<th>Total Carbohydrate</th>
									<th>Total fat</th>
									<th>Saturated fat</th>
									<th>Total sugars</th>
									<th>Cholestorol</th>
									<th>Vitamin D</th>
									<th>Vitamin B12</th>
									<th>Calcium</th>
									<th>Potassium</th>
									<th>Fiber, total dietary</th>
									<th>Total Sodium</th>
									<th>ALA 18:3</th>
									<th>EPA 20:5 n-3</th>
									<th>DHA 22:6 n-3</th>
									<th>Omega 3</th>
								</thead>
								<tbody>
									<td>${calorie} kcal</td>
									<td>${protein} g</td>
									<td>${carbohydrate} g</td>
									<td>${fat} g</td>
									<td>${saturated} g</td>
									<td>${sugar} g</td>
									<td>${cholestorol} mg</td>
									<td>${vitamin_d} mg</td>
									<td>${vitamin_b12} mg</td>
									<td>${calcium} mg</td>
									<td>${potassium} mg</td>
									<td>${fiber} g</td>
									<td>${sodium} mg</th>
									<td>${lipid_18_3} g</td>
									<td>${lipid_20_5} g</td>
									<td>${lipid_22_6} g</td>
									<td>${omega3} g</td>
								</tbody>
							</table>	
						</div>
					</div>
				</div>
				</template>
				<template id="itemSummeryChartDinner">				
				<div class="row p-2 bg-dinner">
					<div class="col-sm-2"><h4>Dinner</h4></div>
					<div class="col-sm-10">
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<th>Total Calorie</th>
									<th>Total protein</th>
									<th>Total Carbohydrate</th>
									<th>Total fat</th>
									<th>Saturated fat</th>
									<th>Total sugars</th>
									<th>Cholestorol</th>
									<th>Vitamin D</th>
									<th>Vitamin B12</th>
									<th>Calcium</th>
									<th>Potassium</th>
									<th>Fiber, total dietary</th>
									<th>Total Sodium</th>
									<th>ALA 18:3</th>
									<th>EPA 20:5 n-3</th>
									<th>DHA 22:6 n-3</th>
									<th>Omega 3</th>
								</thead>
								<tbody>
									<td>${calorie} kcal</td>
									<td>${protein} g</td>
									<td>${carbohydrate} g</td>
									<td>${fat} g</td>
									<td>${saturated} g</td>
									<td>${sugar} g</td>
									<td>${cholestorol} mg</td>
									<td>${vitamin_d} mg</td>
									<td>${vitamin_b12} mg</td>
									<td>${calcium} mg</td>
									<td>${potassium} mg</td>
									<td>${fiber} g</td>
									<td>${sodium} mg</th>
									<td>${lipid_18_3} g</td>
									<td>${lipid_20_5} g</td>
									<td>${lipid_22_6} g</td>
									<td>${omega3} g</td>
								</tbody>
							</table>	
						</div>
					</div>
				</div>
				</template>
				<template id="itemSummeryChartSnack">
				<div class="row p-2 bg-snack">
					<div class="col-sm-2"><h4>Snacks & beverage</h4></div>
					<div class="col-sm-10">
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<th>Total Calorie</th>
									<th>Total protein</th>
									<th>Total Carbohydrate</th>
									<th>Total fat</th>
									<th>Saturated fat</th>
									<th>Total sugars</th>
									<th>Cholestorol</th>
									<th>Vitamin D</th>
									<th>Vitamin B12</th>
									<th>Calcium</th>
									<th>Potassium</th>
									<th>Fiber, total dietary</th>
									<th>Total Sodium</th>
									<th>ALA 18:3</th>
									<th>EPA 20:5 n-3</th>
									<th>DHA 22:6 n-3</th>
									<th>Omega 3</th>
								</thead>
								<tbody>
									<td>${calorie} kcal</td>
									<td>${protein} g</td>
									<td>${carbohydrate} g</td>
									<td>${fat} g</td>
									<td>${saturated} g</td>
									<td>${sugar} g</td>
									<td>${cholestorol} mg</td>
									<td>${vitamin_d} mg</td>
									<td>${vitamin_b12} mg</td>
									<td>${calcium} mg</td>
									<td>${potassium} mg</td>
									<td>${fiber} g</td>
									<td>${sodium} mg</th>
									<td>${lipid_18_3} g</td>
									<td>${lipid_20_5} g</td>
									<td>${lipid_22_6} g</td>
									<td>${omega3} g</td>
								</tbody>
							</table>	
						</div>
					</div>
				</div>
				</template>
				<template id="itemSummeryChartTotal">			
				<div class="row p-2 bg-total">
					<div class="col-sm-2"><h4>Total</h4></div>
					<div class="col-sm-10">
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<th>Total Calorie</th>
									<th>Total protein</th>
									<th>Total Carbohydrate</th>
									<th>Total fat</th>
									<th>Saturated fat</th>
									<th>Total sugars</th>
									<th>Cholestorol</th>
									<th>Vitamin D</th>
									<th>Vitamin B12</th>
									<th>Calcium</th>
									<th>Potassium</th>
									<th>Fiber, total dietary</th>
									<th>Total Sodium</th>
									<th>ALA 18:3</th>
									<th>EPA 20:5 n-3</th>
									<th>DHA 22:6 n-3</th>
									<th>Omega 3</th>
								</thead>
								<tbody>
									<td>${calorie} kcal</td>
									<td>${protein} g</td>
									<td>${carbohydrate} g</td>
									<td>${fat} g</td>
									<td>${saturated} g</td>
									<td>${sugar} g</td>
									<td>${cholestorol} mg</td>
									<td>${vitamin_d} mg</td>
									<td>${vitamin_b12} mg</td>
									<td>${calcium} mg</td>
									<td>${potassium} mg</td>
									<td>${fiber} g</td>
									<td>${sodium} mg</th>
									<td>${lipid_18_3} g</td>
									<td>${lipid_20_5} g</td>
									<td>${lipid_22_6} g</td>
									<td>${omega3} g</td>
								</tbody>
							</table>	
						</div>
					</div>
				</div>
				</template>
			</div>	
		</div>	
	</div>
</section>

<section class="container_section">
	<div class="container">
		<div class="row pb-4">
			<div class="col-sm-4">
				<h4>Sara guideline 1:</h4>
			</div>
			<div class="col-sm-8">			
				It is highly recommended that healthy vegetarian diets include at least one red, green, orange vegetables and fruits a day. 
				Did you choose one of them? 
				You can search Sara’s Star Food using filtering button in <a href="gallery.php">Sara’s Food Gallery</a> page. 
			</div>
		</div>
		<div class="row pb-4">
			<div class="col-sm-4">
				<h4>Sara guideline 2:</h4> 
			</div>
			<div class="col-sm-8">	 
				Healthy vegetarian diets should have protein from a lot of sources including beans, whole grains, milk(lacto-vegan), eggs(ovo-vegan), and fish(pescatarian). 
				Did you choose any one of these vegan protein source? 
				We recommend minimum 50 grams of protein a day. 
				You can search Sara’s Star Food using filtering button in <a href="gallery.php">Sara’s Food Gallery</a> page.
			</div>
		</div>
		<div class="row pb-4">
			<div class="col-sm-4">		
				<h4>Sara guideline 3:</h4>
			</div>
			<div class="col-sm-8">				
				One of the biggest advantage of pescatarian diets lies in having good sources of omega 3.
				Actually there are quite diverse vegan food for omega 3 such as seaweed(미역) and algae, Gim(김, Nori), chia seeds, hemp seeds, walnuts, edamame, soybean oils. 
				However, usually these plants based omega3(ALA)’s efficiency in our body is low and that’s why we recommend fish or other seafood which has abundant DHA and EPA in your daily intake. 
				Do you choose  one of these delicious fish such as Mackerel, salmon, oysters, shrimps tomorrow? 
				We recommend minimum 250~500 mg a day. You can search Sara’s Star Food using filtering button in <a href="gallery.php">Sara’s Food Gallery</a> page.
			</div>
		</div>
		<div class="row pb-4">
			<div class="col-sm-4">
				<h4>***Sara’s Star Food***</h4> 
			</div>
			<div class="col-sm-8">	
				<p>🍅 Red, Green, Orange vegetables and fruits </p>
				<p>🅿 High protein food: one food item which has more than 20 grams of protein</p>
				<p>🅾 High Omega3 food: one food item which has more than 250 mg of omega3</p>
				<p>🇰🇷 Korean food: Korean pescatrian or vegan food</p>
			</div>
		</div>				
	</div>
</section>

<div class="modal fade" id="dialogMeal">
    <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Choose your breakfast, lunch or dinner!</h4>
			  <button type="button" class="close" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body">
				<div class="result">

				</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
    </div>
</div>

<div class="modal fade" id="dialogSnack">
    <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Choose your snacks and beverage!</h4>
			  <button type="button" class="close" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body">
				<div class="result">

				</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
    </div>
</div>
	
<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">Copyright &copy;
    <a href="https://sarafarm.io">Sarafarm.io</a>
  </div>
</footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">

$(".row").on("click", ".card", function(event) {
	event.preventDefault();
	event.stopPropagation();
	
	var dialog = $(this).data("dialog");
	var formData = new FormData();
	
	formData.append("module", "FoodMenu");
	formData.append("dialog", dialog);
	formData.append("page", 0);
	formData.append("itemsPerPage", 25);
	formData.append("card", $(this).data("card"));
	
	var query = new Array();
	for (var pair of formData.entries()) {
		query.push(pair[0] + "=" + pair[1]); 
	}

	requestGet(query.join("&"));
	
	if (dialog) {
		switch (dialog) {
			case "meal":
				$("#dialogMeal").data("formData", formData);
				break;
			case "snack":
				$("#dialogSnack").data("formData", formData);
				break;
		}
	}
});

$("div.modal").on("click", "li a", function(event) {
	event.preventDefault();
	event.stopPropagation();
	
	var formData = new FormData();
	
	formData.append("module", "FoodMenu");
	formData.append("dialog", $(this).data("dialog"));
	formData.append("page", $(this).data("page"));
	formData.append("itemsPerPage", $(this).data("itemsperpage"));
	
	var query = new Array();
	for (var pair of formData.entries()) {
		query.push(pair[0] + "=" + pair[1]); 
	}
	
	requestGet(query.join("&"));
});

$(".module").on("click", function(event) {
	event.preventDefault();
	event.stopPropagation();
	
	var formData = new FormData();
	
	formData.append("module", "SummaryChart");
	
	var items = ["breakfast","lunch","dinner","snack"];
	items.forEach(function(item, index) { 
		for (var index = 0; index < 5; index++) {
			var card = item + "_0" + index;
			var foodCode = $("section div[data-card='"+card+"']").find("img").data("foodcode");
			
			formData.append(item+"[]", foodCode);
		}
	});
	
	var query = new Array();
	for (var pair of formData.entries()) {
		query.push(pair[0] + "=" + pair[1]); 
	}

	requestGet(query.join("&"));
});

$("#dialogMeal .result").on("click", ".card img", function(event) {
	event.preventDefault();
	event.stopPropagation();
	
	if ($(this).parent(".card").hasClass('border-warning')) {
		$(this).parent(".card").removeClass('border-warning');
	} else {
		$(this).parent(".card").addClass('border-warning');
	}
	
	$("#dialogMeal").modal("hide");
	
	var formData = $("#dialogMeal").data("formData");
	var card = formData.get("card");
	var foodCode = $(this).data("foodcode");
	
	$("section div[data-card='"+card+"']").find("img").data("foodcode", foodCode);
	$("section div[data-card='"+card+"']").find("img").prop("src", "img/food/"+foodCode+".jpg");	
});

$("#dialogSnack .result").on("click", ".card img", function(event) {
	event.preventDefault();
	event.stopPropagation();
	
	if ($(this).parent(".card").hasClass('border-warning')) {
		$(this).parent(".card").removeClass('border-warning');
	} else {
		$(this).parent(".card").addClass('border-warning');
	}

	$("#dialogSnack").modal("hide");
	
	var formData = $("#dialogSnack").data("formData");
	var card = formData.get("card");
	var foodCode = $(this).data("foodcode");
	
	$("section div[data-card='"+card+"']").find("img").data("foodcode", foodCode);	
	$("section div[data-card='"+card+"']").find("img").prop("src", "img/food/"+foodCode+".jpg");
});

function getPagination(pages, page, itemsPerPage, dialog) {
	var snippet = new Array();
	snippet.push('<div class="row">');	
	snippet.push('<div class="col">');	
	snippet.push('<div class="nav-scroller py-1 mb-2">');	 
	snippet.push('<nav class="nav d-flex justify-content-center">');	 
	snippet.push('<ul class="pagination pagination-lg flex-sm-wrap">');	 
	for (var index = 1 ; index <= pages; index++) {
		snippet.push('<li class="page-item' + ((page == index) ? ' active' : '') + '">');
		snippet.push('<a class="page-link" data-page="'+index+'" data-itemsperpage="'+itemsPerPage+'" data-dialog="'+dialog+'">'+index+'</a>');	
		snippet.push('</li>');	
	}
	snippet.push('</ul>');	 
	snippet.push('</nav>');	
	snippet.push('</div>');	
	snippet.push('</div>');	
	snippet.push('</div>');	
	
	return snippet;
}

function requestGet(data) {
	$.get(
		"/api.sarafarm.io/", data
	).done(
		function( data ) {
			var obj = JSON.parse(data);
			
			switch(obj.module) {
				case "FoodMenu":
					if (obj.data) {
						var snippet = new Array();
						
						for (var index = 0; index < obj.data.length; index+=3) {
							snippet.push('<div class="row mb-3">');
								for (var column = 0; column < 3; column++) {
									snippet.push('<div class="col">');
									snippet.push('<div class="card h-100 cursor-pointer">');
									if (index + column < obj.data.length) {
										var item = obj.data[index + column];
										snippet.push('<img src="'+item["image"]+'" data-foodcode="'+item["food_code"]+'" class="img-fluid rounded" title="'+item["main_food_description"]+'">');
									} else {
										snippet.push('<img src="img/food/00000000.jpg" data-foodcode="00000000" class="img-fluid rounded" title="sarafarm">');
									}
									snippet.push('</div>');
									snippet.push('</div>');
								}
							snippet.push('</div>');		
						}
						
						if (obj.dialog) {
							snippet = snippet.concat(getPagination(obj.pages, obj.page, obj.itemsPerPage, obj.dialog));
							switch (obj.dialog) {
								case "meal":
									$('#dialogMeal .result').html(snippet.join("\n"));	
									$("#dialogMeal").modal();
									break;
								case "snack":
									$('#dialogSnack .result').html(snippet.join("\n"));
									$("#dialogSnack").modal();
									break;
							}
						}						
					}
					break;
				case "SummaryChart":
					if (obj.data) {
						var itemTpl = $('#itemSummeryFood').html().split(/\$\{(.+?)\}/g);
						var items = obj.data[0];
						$('.viewSummeryFood .card-columns').empty();
						$('.viewSummeryFood .card-columns').append(
							items.map(function (item) {
								console.log(item);
								item.star_omega = (item.star_omega == 1) ? "&#127358;" : "";
								item.star_protein = (item.star_protein == 1) ? "&#127359;" : "";
								item.star_rgo = (item.star_rgo == 1) ? "&#127813;" : "";
								item.star_taegeuk = (item.star_taegeuk == 1) ? "&#127472;&#127479;" : "";
								return itemTpl.map(render(item)).join('');
							})
						);

						$('.viewSummeryChart').empty();
						
						var snippet = new Array();
						snippet.push('<div class="row mb-3"><div class="col">');
						snippet.push('<button data-toggle="collapse" class="btn btn-outline-warning btn-block" data-target="#foodCards">Show or hide food what you chose.</button>');
						snippet.push('</div></div>');
						$('.viewSummeryChart').append(snippet.join("\n"));
						
						$.each( obj.data[1], function( key, items ) {
							var name = key.charAt(0).toUpperCase() + key.slice(1);
							var itemTpl = $('#itemSummeryChart'+name).html().split(/\$\{(.+?)\}/g);
							$('.viewSummeryChart').append(
								items.map(function (item) {
									return itemTpl.map(render(item)).join('');
								})
							);
						});	
					}
					break;
			}
		}
	).fail( function(xhr, textStatus, error) {
		console.log(xhr.status + " :: " + xhr.statusText + " :: " + xhr.responseText);
    });
}

function render(props) {
  return function(tok, i) { return (i % 2) ? props[tok] : tok; };
}

</script>
</body>
</html>

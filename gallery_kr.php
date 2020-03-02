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
$veganCategory = getParam($_GET, "veganCategory");
$starFoodCategory = getParam($_GET, "starFoodCategory");
$itemsPerPage = getParam($_GET, "itemsPerPage", "int");
$page = getParam($_GET, "page", "int");
$queryString = getQueryString(array("itemsPerPage" => $itemsPerPage, "suggested" => $suggested, "foodCode" => $foodCode, "searchText" => $searchText, "foodCategoryCode" => $foodCategoryCode, "veganCategory" => $veganCategory, "starFoodCategory" => $starFoodCategory));
$foodCategoryOption = array();
$veganCategoryOption = array();
$starFoodCategoryOption = array();

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
	if (strlen($veganCategory) > 0) {
		$column = $mysqli->real_escape_string($veganCategory);
		array_push($where, sprintf("%s=1 ", $column));		
	}	
	if (strcmp($starFoodCategory, "omega3") == 0) {
		array_push($where, "omega3 >= 0.25 ");
	}
	if (strcmp($starFoodCategory, "protein") == 0) {	
		array_push($where, "protein > 20 ");
	}
	if (strcmp($starFoodCategory, "rgo") == 0) {	
		array_push($where, "food_mark = 'rgo' ");
	}
	if (strcmp($starFoodCategory, "korean") == 0) {	
		array_push($where, "food_mark = 'korean' ");	
	}	
	if (count($where) > 0) {
		// add spaceholder item for AND
		array_unshift($where, "");
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

	// veganCategoryOption
	$veganCategoryOption = array("vegan" => "Vegan",  "lacto_vegan" => "Lacto Vegan", "ovo_vegan" => "Ovo Vegan", "pescatarian" => "Pescatarian");

	// starFoodCategoryOption
	$starFoodCategoryOption = array("omega3" => "&#127358; High omega3 food", 
									"protein" => "&#127359; High protein food", 
									"rgo" => "&#127813 Red, Green, Orange vegetables &amp; fruits", 
									"korean" => "&#127472;&#127479; Korean food");

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
.card-text {
	text-shadow: 1px 1px #CECECE;
}
.cursor-pointer {
	cursor: pointer;
}	  
  </style>
</head>
<body>

<div class="jumbotron bg-cover">
  <div class="overlay"></div>
  <div class="container">
  <h1>사라잇</h1>
  <p>데이터 기반 페스카테리언 다이어트 어플리케이션</p>   
  </div>
</div>
  
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<a class="navbar-brand" href="/index_kr.php">
    <img src="img/saraeat.png" alt="Logo" style="width:40px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="/gallery_kr.php">푸드갤러리</a>
      </li>	
      <li class="nav-item">
        <a class="nav-link" href="/foodmenu_kr.php">사라게임</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/veggie.php">비건 식단</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/aboutus_kr.php">About us</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="/gallery.php">English</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section">
<div class="container-fluid">

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="filter">
<div class="row pb-4">
  <div class="col-sm-7">
	<h2>내일 뭐 드실 거에요?</h2>
	<p>식품에 대한 분량, 칼로리 등 영양정보를 원하시면 각 식품을 클릭하세요.</p>
  </div>
  <div class="col-sm-5">
  	<div class="btn-toolbar justify-content-end">
	<button type="button" class="btn mr-1 mb-1">Number of rows:</button>
    <select class="btn btn-outline-info mr-1 mb-1" id="itemsPerPage" name="itemsPerPage" onchange="if(this.value != 0) { this.form.submit(); }">
	<?php for ($index = 25; $index <= 250; $index+=25): ?>
	<?php printf("<option value=\"%d\"%s>%s</option>", $index, ($index == $itemsPerPage) ? " selected=\"selected\"": "", $index); ?>
	<?php endfor; ?>
		</select>	

		<button type="button" class="btn btn-outline-info mr-1 mb-1">Total: <?php echo $total; ?></button>
		<button type="button" class="btn btn-outline-warning mr-1 mb-1" data-toggle="collapse" data-target="#filter">Filter</button>		
		<button type="submit" class="btn btn-outline-success mr-1 mb-1">Submit »</button>
	</div>
  </div>
</div>

<div id="filter" class="collapse">
	<div class="row pb-4">
		<div class="col">			
			<label for="veganCategory" class="mr-sm-2">Vegan category:</label>
			<select class="form-control" id="veganCategory" name="veganCategory">
				<option value="">&nbsp;</option>
				<?php foreach ($veganCategoryOption as $key => $value): ?>
				<?php echo sprintf("<option value=\"%s\"%s>%s</option>", $key, (strcmp($veganCategory, $key) == 0) ? "selected=\"selected\"" : "", $value); ?>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col">			
			<label for="starFoodCategory" class="mr-sm-2">Sara's star food:</label>
			<select class="form-control" id="starFoodCategory" name="starFoodCategory">
				<option value="">&nbsp;</option>
				<?php foreach ($starFoodCategoryOption as $key => $value): ?>
				<?php echo sprintf("<option value=\"%s\"%s>%s</option>", $key, (strcmp($starFoodCategory, $key) == 0) ? "selected=\"selected\"" : "", $value); ?>
				<?php endforeach; ?>
			</select>
		</div>			
		<div class="col">
			<label for="foodCode" class="mr-sm-2">Food code:</label>
			<input type="text" class="form-control mb-2 mr-sm-2" id="foodCode" name="foodCode" value="<?php echo $foodCode; ?>">
		</div>			
		<div class="col">
			<label for="searchText" class="mr-sm-2">Search text:</label>
			<input type="text" class="form-control mb-2 mr-sm-2" id="searchText" name="searchText"  value="<?php echo $searchText; ?>">
		</div>	
		<div class="col">			
			<label for="foodCategoryCode" class="mr-sm-2">Food category:</label>
			<select class="form-control" id="foodCategoryCode" name="foodCategoryCode">
				<option value="">&nbsp;</option>
				<?php foreach ($foodCategoryOption as $key => $value): ?>
				<?php echo sprintf("<option value=\"%s\"%s>%s</option>", $key, ($key == $foodCategoryCode) ? "selected=\"selected\"" : "", $value); ?>
				<?php endforeach; ?>
			</select>
		</div>	
	</div>
</div>

</form>

		
<?php if (count($data) > 0) { ?>
<?php for ($index = 0; $index < count($data); $index+=5): ?>
	<div class="row mb-3">	
		<?php for ($column = 0; $column < 5; $column++){ ?>
		<div class="col">
			<div class="card h-100 cursor-pointer">
			<?php if ($index + $column < count($data)) { ?>				
			<?php $item = $data[$index + $column]; ?>
				<img src="<?php echo $item["image"]; ?>" class="img-fluid rounded" title="<?php echo $item["food_name_kr"]; ?>">
				<div class="card-img-overlay overflow-auto" data-foodcode="<?php echo $item["food_code"]; ?>">
					<p class="card-text">&#128070; 여기를 클릭하세요!</p>
				</div>
			<?php } else { ?>
				<img src="img/food/00000000.jpg" class="img-fluid rounded" title="saraeat">
			<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>		
<?php endfor; ?>	
<?php } ?>

	
	  <div class="row">
	<div class="col">

<div class="nav-scroller py-1 mb-2"> 
	<nav class="nav d-flex justify-content-center"> 
    	<ul class="pagination pagination-sm flex-sm-wrap"> 
		<?php for($index = 1 ; $index <= $pages; $index++): ?>
	<li class="page-item<?php echo ($page == $index) ? " active" : ""; ?>">
		<a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $index.$queryString; ?>"><?php echo $index; ?></a>
	</li>
<?php endfor; ?>
        </ul> 
    </nav> 
</div>

</div>
</div>

</div>
</section>

<div class="modal fade" id="dialogFoodInfo">
    <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Food Info</h4>
			  <button type="button" class="close" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body viewDialogFoodInfo">

			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
    </div>
	<template id="viewDialogFoodInfo">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<caption>&#127358; High omega3 food, &#127359; High protein food, &#127813 Red, Green, Orange vegetables &amp; fruits, &#127472;&#127479; Korean food</caption>
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
		<img src="${image}" class="img-fluid rounded" title="${main_food_description}">		
	</template>
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
$(".card").on("click", ".card-img-overlay", function(event) {
	event.preventDefault();
	event.stopPropagation();
	
	var formData = new FormData();
	
	formData.append("module", "FoodInfo");
	formData.append("foodCode", $(this).data("foodcode"));
	
	$("#dialogFoodInfo").data("formData", formData);
	
	var query = new Array();
	for (var pair of formData.entries()) {
		query.push(pair[0] + "=" + pair[1]); 
	}

	requestGet(query.join("&"));
});

function requestGet(data) {
	$.get(
		"/api.sarafarm.io/", data
	).done(
		function( data ) {
			var obj = JSON.parse(data);
			
			switch(obj.module) {
				case "FoodInfo":
					if (obj.data) {
						var title = obj.data[0].food_name_kr;
						var view = obj.data;
						var viewTpl = $('#viewDialogFoodInfo').html().split(/\$\{(.+?)\}/g);
						
						obj.data[0].star_omega = (obj.data[0].star_omega == 1) ? "&#127358;" : "";
						obj.data[0].star_protein = (obj.data[0].star_protein == 1) ? "&#127359;" : "";
						obj.data[0].star_rgo = (obj.data[0].star_rgo == 1) ? "&#127813;" : "";
						obj.data[0].star_taegeuk = (obj.data[0].star_taegeuk == 1) ? "&#127472;&#127479;" : "";
						
						$('.viewDialogFoodInfo').html(view.map(function (item) {
							return viewTpl.map(render(item)).join('');
						}));
						
						$('#dialogFoodInfo .modal-title').html(title);
						$("#dialogFoodInfo").modal();
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

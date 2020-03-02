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
  <title>Sarafarm Food And Nutrient Selection</title>
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
	  
  </style>
</head>
<body>

<div class="jumbotron bg-cover">
  <div class="overlay"></div>
  <div class="container">
  <h1>SaraEat</h1>
  <p>Data Based Pescatarian Diet Application</p>   
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
      <li class="nav-item">
        <a class="nav-link" href="/veggie.php">Vegan Weekly Plan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/fndds.php">Food and Nutrient Database for Dietary Studies</a>
      </li>	  
    </ul>
  </div>  
</nav>

<section class="container_section">
<div class="container-fluid">

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="filter">
<div class="row pb-4">
  <div class="col-sm-7"><h2>Sarafarm Food And Nutrient Selection</h2></div>
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
		<div class="col-sm-3">			
			<label for="snackCategoryCode" class="mr-sm-2">Snack category:</label>
			<select class="form-control" id="snackCategoryCode" name="snackCategoryCode">
				<option value="0">&nbsp;</option>
				<?php foreach ($snackCategoryOption as $key => $value): ?>
				<?php echo sprintf("<option value=\"%s\"%s>%s</option>", $value, ($value == $snackCategoryCode) ? "selected=\"selected\"" : "", $key); ?>
				<?php endforeach; ?>
			</select>
		</div>	
		<div class="col-sm-3">
			<label for="foodCode" class="mr-sm-2">Food code:</label>
			<input type="text" class="form-control mb-2 mr-sm-2" id="foodCode" name="foodCode" value="<?php echo $foodCode; ?>">
		</div>			
		<div class="col-sm-3">
			<label for="searchText" class="mr-sm-2">Search text:</label>
			<input type="text" class="form-control mb-2 mr-sm-2" id="searchText" name="searchText"  value="<?php echo $searchText; ?>">
		</div>	
		<div class="col-sm-3">			
			<label for="foodCategoryCode" class="mr-sm-2">Food category:</label>
			<select class="form-control" id="foodCategoryCode" name="foodCategoryCode">
				<option value="">&nbsp;</option>
				<?php foreach ($foodCategoryOption as $key => $value): ?>
				<?php echo sprintf("<option value=\"%s\"%s>%s</option>", $key, ($key == $foodCategoryCode) ? "selected=\"selected\"" : "", $value); ?>
				<?php endforeach; ?>
			</select>
		</div>	
	</div>
	
<!--
	<div class="row pb-4">
		<div class="col-sm-12">
			<p><?php echo $sql ?></p>
		</div>	
	</div>	 
//-->
</div>

</form>

	<div class="row mb-3">
		<div class="col">
<?php if (count($data) > 0) { ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered">
<?php
foreach ($data as $index => $item) {
	$columns = array_keys($item);
	
	array_shift($columns);
	
	if ($index == 0) {
		$labels = array();
		foreach ($columns as $column) {
			array_push($labels, $tags[$column]);
		}
		echo sprintf("<tr><th>%s</th></tr>\n", implode("</th><th>", $labels));
	}	
	$values = array();
	foreach ($columns as $column) {
		if (strcmp($column, "image") == 0) {
			$item[$column] = sprintf("<img src=\"%s\" id=\"%s\" class=\"img-thumbnail profileImageButton\" title=\"%s\">", $item[$column], $item["food_code"], $item["food_code"]);
		}
		if (in_array($column, array("vegan", "lacto_vegan", "ovo_vegan", "pescatarian"))) {
			$item[$column] = sprintf("<input type=\"checkbox\" disabled=\"disabled\" data-id=\"%d\" name=\"%s\" %s data-size=\"sm\" data-toggle=\"toggle\" data-on=\"Yes\" data-off=\"No\" value=\"1\" data-onstyle=\"success\" data-offstyle=\"danger\">", $item["food_code"], $column, (intval($item[$column]) == 1) ? "checked=\"checked\"" : ""); 
		}
		
		array_push($values, $item[$column]);
	}

	echo sprintf("<tr style=\"background-color:#E7FFDF;\"><td>%s</td></tr>\n", implode("</td><td>", $values));
}	
?>	
	</table>
	</div>
	<?php } ?>
	</div>
	</div>
	
	  <div class="row">
	<div class="col">

<div class="nav-scroller py-1 mb-2"> 
	<nav class="nav d-flex justify-content-center"> 
    	<ul class="pagination pagination-sm flex-sm-wrap"> 
		<?php for($index = 1 ; $index <= $pages; $index++): ?>
	<li class="page-item<?php echo ($page == $index) ? " active" : ""; ?>">
		<a class="page-link" href="food.php?page=<?php echo $index.$queryString; ?>"><?php echo $index; ?></a>
	</li>
<?php endfor; ?>
        </ul> 
    </nav> 
</div>

</div>
</div>

</div>
</section>

<style>
      .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 5px solid #ccc;
        border-radius: 3px;
        margin-top: 20px;
        width: 512px;
        height: 521px;
      }

	  .cropit-image-input {
		color: white;
		border: none;
		display:inline;
	  }
	  
	  .profileImageClose {
		display: inline;
		float:right;
		font-size: 12pt;
	  }
	  
	  .profileImageDelete, .profileImageButton {
		cursor:pointer;
	  }
	  
      .cropit-preview-image-container {
        cursor: move;
      }

      .cropit-preview-background {
        opacity: .2;
        cursor: auto;
      }

      .image-size-label {
		margin-left: 10px;
        margin-top: 10px;
      }

      input.image-editor {
        /* Use relative position to prevent from being covered by image background */
        position: relative;
        z-index: 10;
        display: block;
      }

      button.image-editor {
        margin-top: 10px;
      }
	  
	.popup_overlay {
		position: fixed;
		display: none;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0,0,0,0.5);
		z-index: 8;
		cursor: pointer;
	}  
	.popup {
		position: absolute;
		top: 50%;
		left: 50%;
		color: white;
		transform: translate(-50%,-50%);
		-ms-transform: translate(-50%,-50%);
	}
	.popup .settings {
		display:table-cell;
	}
	.popup button, .popup input[type=range] {
		display: table-cell;
		vertical-align: middle;
	}

	.profileImagePreview {
		background-color: white;
	}
</style>

	<div class="popup_overlay">
	<div class="popup">
    <div class="image-editor">
		<div class="headline">
			<input type="file" id="file" name="file" class="cropit-image-input" accept="image/jpeg, image/gif, image/png">
			<div class="profileImageClose">[ X ]</div>
		</div>
      <div class="cropit-preview"></div>
      <div class="image-size-label">
        image size slider
      </div>
	  <div class="settings">
		<input type="range" class="cropit-image-zoom-input">
		<button class="rotate-ccw">left rotate</button>
		<button class="rotate-cw">right rotate</button>
		<button class="export" disabled="disabled">ready</button>
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
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>  
  <script src="/js/jquery.cropit.js"></script>
  
    <script>
	// https://www.jqueryscript.net/other/Simple-jQuery-Image-Zoom-Pan-Crop-Plugin-Cropit.html
	$(function() {
        $('.image-editor').cropit({
          exportZoom: 1.0,
		  maxZoom: 4.0,
		  smallImage: 'allow',
          imageBackground: true,
		  allowDragNDrop: false,
          imageBackgroundBorderWidth: 20,
          imageState: {
            src: '/img/sarafarm.io.png',
          },
        });

        $('.rotate-cw').on("click", function(event) {
			event.preventDefault();
			event.stopPropagation();
          $('.image-editor').cropit('rotateCW');
        });
        $('.rotate-ccw').on("click", function(event) {
			event.preventDefault();
			event.stopPropagation();
          $('.image-editor').cropit('rotateCCW');
        });

        $('.export').on("click", function(event) {
			event.preventDefault();
			event.stopPropagation();
		
			var imageData = $('.image-editor').cropit('export');
			
			$("#"+foodCode).prop("src", imageData);
		  
			var formData = new FormData();
			formData.append("module", "foodimage");			
			formData.append("file", $('#file')[0].files[0]);
			formData.append("imageData", imageData);
			formData.append("foodCode", foodCode);
			
			// $.ajax({
				// url: "https://api.sarafarm.io/",
				// type: "POST",
				// data: formData,
				// enctype: 'multipart/form-data',
				// processData: false, // tell jQuery not to process the data
				// contentType: false, // tell jQuery not to set contentType
				// dataType: 'json'
			// }).done(function(json) {
				// if (json.foodCode == foodCode) {
					// $("div.popup_overlay").hide();
				// }
			// });
        });
      });
	  
	  var foodCode = '';
	  
	  $(".profileImageClose").on("click", function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		$("div.popup_overlay").hide();
	  });
	  
	  $(".profileImageButton").on("click", function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		foodCode = $(this).prop('id');
		
		$('.image-editor').cropit('imageSrc', $(this).prop('src'));
		$("div.popup_overlay").show();
	  });
	  
		$('.table').on('change', 'input[type=checkbox]', function(e) {
			event.preventDefault();
			event.stopPropagation();
			
			// $(this).bootstrapToggle('disable');
			
			// var formData = new FormData();
			// formData.append("module", "vegetarian");
			// formData.append("column", this.name);
			// formData.append("checked", this.checked ? 1 : 0);
			// formData.append("foodCode", $(this).data("id"));
			
			// $.ajax({
				// url: "https://api.sarafarm.io/",
				// type: "POST",
				// data: formData,
				// processData: false, // tell jQuery not to process the data
				// contentType: false, // tell jQuery not to set contentType
				// dataType: 'json'
			// }).done(function(json) {
				// if (json.foodCode == formData.get("foodCode")) {
					// $(':checkbox[data-id="'+formData.get("foodCode")+'"]').bootstrapToggle('enable');	
				// }
			// });
		});	  
	  
    </script>



</body>
</html>

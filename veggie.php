<?php

$id = intval($_GET["id"]);
$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$data = array();

try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$weekdays = array("", "Monday", "Tuesday", "Wednesday", "Thusday", "Friday", "Saturday", "Sunday");
	
	$sql = "SELECT veggie_weekly.weekday, ";
	$sql .= "breakfast.id AS breakfast_id, breakfast.name AS breakfast_name, breakfast.kcal AS breakfast_kcal, ";
	$sql .= "lunch.id AS lunch_id, lunch.name AS lunch_name, lunch.kcal as lunch_kcal, ";
	$sql .= "supper.id AS supper_id, supper.name AS supper_name, supper.kcal AS supper_kcal, ";
	$sql .= "breakfast.kcal + lunch.kcal + supper.kcal AS total_kcal ";
	$sql .= "FROM veggie_weekly ";
	$sql .= "INNER JOIN (SELECT veggie_recipes.id, veggie_recipes.name, veggie_nutrition.kcal FROM veggie_recipes INNER JOIN veggie_nutrition ON (veggie_recipes.id = veggie_nutrition.recipe_id)) AS breakfast ON (breakfast.id = veggie_weekly.breakfast_id) ";
	$sql .= "INNER JOIN (SELECT veggie_recipes.id, veggie_recipes.name, veggie_nutrition.kcal FROM veggie_recipes INNER JOIN veggie_nutrition ON (veggie_recipes.id = veggie_nutrition.recipe_id)) AS lunch ON (lunch.id = veggie_weekly.lunch_id) ";
	$sql .= "INNER JOIN (SELECT veggie_recipes.id, veggie_recipes.name, veggie_nutrition.kcal FROM veggie_recipes INNER JOIN veggie_nutrition ON (veggie_recipes.id = veggie_nutrition.recipe_id)) AS supper ON (supper.id = veggie_weekly.supper_id) ";
	$sql .= "WHERE veggie_weekly.month = 1;";
	
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->weekday = $weekdays[$row["weekday"]];
			$item->breakfast_id = intval($row["breakfast_id"]);	
			$item->breakfast_name = trim($row["breakfast_name"]);
			$item->breakfast_kcal = intval($row["breakfast_kcal"]);	
			$item->lunch_id = intval($row["lunch_id"]);	
			$item->lunch_name = trim($row["lunch_name"]);
			$item->lunch_kcal = intval($row["lunch_kcal"]);
			$item->supper_id = intval($row["supper_id"]);	
			$item->supper_name = trim($row["supper_name"]);
			$item->supper_kcal = intval($row["supper_kcal"]);
			$item->total_kcal = intval($row["total_kcal"]);
			array_push($data, $item);
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
  <style>
  .carousel-inner img {
    width: 100%;
    height: 100%;
  }
  .carousel-caption {
	top: 50%;
	transform: translateY(-50%);
	bottom: initial;
	text-align: left;
	text-shadow: 2px 2px #000;
  }
  .item {
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	transform-style: preserve-3d;
  }
  
.jumbotron {
  text-align: center;
  padding-top: 4rem;
  padding-bottom: 4rem;
  text-shadow: 2px 2px #000;
  margin-bottom:0;
}  
.card-text {
	text-shadow: 1px 1px #CECECE;
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
      <li class="nav-item active">
        <a class="nav-link" href="/veggie.php">Vegan Weekly Plan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/food.php">Sarafarm Food And Nutrient Database</a>
      </li>
    </ul>
  </div>  
</nav>

<?php if (count($data) > 0): ?>
<section class="container_section" id="weekly">
<div class="container mt-3">
<?php foreach ($data as $index => $item) { ?>
<div class="row mt-3">
<div class="col-sm-2">
  <h4><?php echo $item->weekday; ?></h4>
  <p><?php echo $item->total_kcal; ?> kcal</p>
</div>  
<div class="col-sm-10">
<div class="card-deck">
  <div class="card border-success" id="card10<?php printf('%02d', $index); ?>">
	<img class="card-img-top" src="img/veggie_<?php printf('%02d', $item->breakfast_id); ?>.jpg" title="<?php echo $item->breakfast_name; ?>">
    <div class="card-img-overlay text-center">
	  <h4 class="card-title">&#9749;&nbsp;<a href="recipe.php?id=<?php echo $item->breakfast_id; ?>&refer=card10<?php printf('%02d', $index); ?>" class="stretched-link"><?php echo $item->breakfast_kcal; ?> kcal</a></h4>
      <p class="card-text"><?php echo $item->breakfast_name; ?></p>
    </div>
  </div>
  <div class="card border-info" id="card20<?php printf('%02d', $index); ?>">
	<img class="card-img-top" src="img/veggie_<?php printf('%02d', $item->lunch_id); ?>.jpg" title="<?php echo $item->lunch_name; ?>">  
    <div class="card-img-overlay text-center">
	  <h4 class="card-title">&#127860;&nbsp;<a href="recipe.php?id=<?php echo $item->lunch_id; ?>&refer=card20<?php printf('%02d', $index); ?>" class="stretched-link"><?php echo $item->lunch_kcal; ?> kcal</a></h4>
      <p class="card-text"><?php echo $item->lunch_name; ?></p>
    </div>
  </div>
  <div class="card border-danger" id="card30<?php printf('%02d', $index); ?>">
	<img class="card-img-top" src="img/veggie_<?php printf('%02d', $item->supper_id); ?>.jpg" title="<?php echo $item->supper_name; ?>">  
    <div class="card-img-overlay text-center">
	  <h4 class="card-title">&#127867;&nbsp;<a href="recipe.php?id=<?php echo $item->supper_id; ?>&refer=card30<?php printf('%02d', $index); ?>" class="stretched-link"><?php echo $item->supper_kcal; ?> kcal</a></h4>
      <p class="card-text"><?php echo $item->supper_name; ?></p>
    </div>
  </div>
</div>
</div>
</div>
<?php } ?>
</div>
</section>

<?php else: ?>
  <p>Default Content</p>
<?php endif; ?>

<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">Copyright &copy;
    <a href="https://sarafarm.io">Sarafarm.io</a>
	and Image by <a href="https://pixabay.com/users/silviarita-3142410/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2756467">silviarita</a> from <a href="https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2756467">Pixabay</a>
  </div>
</footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
<script>

</script>  
</body>
</html>
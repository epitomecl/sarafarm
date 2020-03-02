<?php

$id = intval($_GET["id"]);
$refer = trim($_GET["refer"]);
$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$data = NULL;

try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT veggie_recipes.* FROM veggie_recipes WHERE id = %d;";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->name = trim($row["name"]);
			$item->author = trim($row["author"]);	
			$item->source = trim($row["source"]);
			$item->description = trim($row["description"]);	
			$item->servings = intval($row["servings"]);
			$item->prep = intval($row["prep"]);
			$item->cook = intval($row["cook"]);	
			$item->type_of_vegetarian = intval($row["type_of_vegetarian"]);
			$data->recipe = array($item);
		}
	}

	$methods = array();	
	$sql = "SELECT veggie_methods.* FROM veggie_methods WHERE recipe_id = %d ORDER BY step;";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$method = trim($row["method"]);
			array_push($methods, $method);
		}
		$data->methods = $methods;
	}
	
	$sql = "SELECT veggie_nutrition.* FROM veggie_nutrition WHERE recipe_id = %d;";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->kcal = trim($row["kcal"]);
			$item->fat = trim($row["fat"]);	
			$item->saturates = trim($row["saturates"]);
			$item->carbs = trim($row["carbs"]);	
			$item->sugars = intval($row["sugars"]);
			$item->fibre = intval($row["fibre"]);
			$item->protein = intval($row["protein"]);	
			$item->salt = intval($row["salt"]);
			$data->nutrition = array($item);
		}
	}

	$ingredients = array();	
	$sql = "SELECT veggie_ingredients.*, veggie_parts.name FROM veggie_ingredients ";
	$sql .= "INNER JOIN veggie_parts ON (veggie_ingredients.parts_id = veggie_parts.id) ";
	$sql .= "WHERE veggie_ingredients.recipe_id = %d ";
	$sql .= "ORDER BY parts_id;";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$title = trim($row["name"]);
			
			$item = new stdClass;
			$item->amount = doubleval($row["amount"]);
			$item->unit = trim($row["unit"]);	
			$item->ingredient = trim($row["ingredient"]);
			
			if (array_key_exists($title, $ingredients)) {
				array_push($ingredients[$title], $item);
			} else {
				$ingredients[$title] = array($item);				
			}
		}
		$data->ingredients = $ingredients;
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
  <title>SaraFarm</title>
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
        <a class="nav-link" href="/foodmenu.php">SaraGame</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="/veggie.php#<?php echo trim($refer); ?>">Vegan Weekly Plan</a>
      </li>	
      <li class="nav-item">
        <a class="nav-link" href="/aboutus.php">About us</a>
      </li> 
    </ul>
  </div>  
</nav>

<?php if (isset($data)): ?>
<section class="container_section" id="recipe">
<div class="container mt-3">

<?php foreach ($data->recipe as $index => $item) { ?>			
<div class="row mt-3">
<div class="col-sm-12 mx-auto">
<h2><?php echo $item->name; ?></h2>
<p>by <?php echo $item->author; ?> (<?php echo $item->source; ?>)</p>
<p><?php echo $item->description; ?></p>
</div>
</div>
<?php } ?>

</div>
</section>

<section class="container_section bg-light" id="nutrition">
<div class="container mt-3">

<?php foreach ($data->nutrition as $index => $item) { ?>
<div class="row mt-3">
<div class="col-sm-12 mx-auto">
<p>Nutrition: per serving</p>
<div class="btn-toolbar justify-content-center">
<button type="button" class="btn btn-outline-success mr-1 mb-1"><h4>kcal</h4><p><?php echo $item->kcal; ?></p></button>
<button type="button" class="btn btn-outline-primary mr-1 mb-1"><h4>fat</h4><p><?php echo $item->fat; ?>g</p></button>
<button type="button" class="btn btn-outline-warning mr-1 mb-1"><h4>saturates</h4><p><?php echo $item->saturates; ?>g</p></button>
<button type="button" class="btn btn-outline-primary mr-1 mb-1"><h4>carbs</h4><p><?php echo $item->carbs; ?>g</p></button>
<button type="button" class="btn btn-outline-danger mr-1 mb-1"><h4>sugars</h4><p><?php echo $item->sugars; ?>g</p></button>
<button type="button" class="btn btn-outline-primary mr-1 mb-1"><h4>fibre</h4><p><?php echo $item->fibre; ?>g</p></button>
<button type="button" class="btn btn-outline-info mr-1 mb-1"><h4>protein</h4><p><?php echo $item->protein; ?>g</p></button>
<button type="button" class="btn btn-outline-primary mr-1 mb-1"><h4>salt</h4><p><?php echo $item->salt; ?>g</p></button>
</div>
</div>
</div>
<?php } ?>

</div>
</section>

<section class="container_section" id="steps">
<div class="container mt-3">

<?php foreach ($data->recipe as $index => $item) { ?>			
<div class="row mt-3">
<div class="col-sm-4"><h4>&#128517; Preparation: <?php echo $item->prep; ?>min</h4></div>
<div class="col-sm-4"><h4>&#127859; Cooking: <?php echo $item->cook; ?>min</h4></div>
<div class="col-sm-4"><h4>&#127857; Servings: <?php echo $item->servings; ?></h4></div>
</div>
<?php } ?>

<?php foreach ($data->ingredients as $title => $group) { ?>
<div class="row mt-3">
<div class="col-sm-12">
	<div class="card">
	  <div class="card-body">
		<h4 class="card-title"><?php echo ucfirst($title); ?></h4>
		<p class="card-text">
		<ul>
<?php foreach ($group as $index => $item) { ?>
			<li><?php echo $item->amount; ?> <?php echo $item->unit; ?> <?php echo $item->ingredient; ?></li>
<?php } ?>
		</ul>
		</p>
	  </div>
	</div>
</div> 
</div>
<?php } ?>

<?php foreach ($data->methods as $index => $method) { ?>			
<div class="row mt-3">
<div class="col-sm-12">
	<div class="card bg-light">
	  <div class="card-body">
		<h4 class="card-title">Step <?php echo $index + 1; ?>:</h4>
		<p class="card-text"><?php echo $method; ?></p>
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
    <a href="https://sarafarm.io">SaraFarm.io</a>
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
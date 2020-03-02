<?php

$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$profiles = array();
	
try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT id, membership, firstname, lastname, phone, email, wallet FROM profile;";
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->id = $row["id"];
			$item->membership = $row["membership"];			
			$item->firstname = trim($row["firstname"]);
			$item->lastname = trim($row["lastname"]);
			$item->phone = trim($row["phone"]);
			$item->email = trim($row["email"]);
			$item->wallet = trim($row["wallet"]);
			array_push($profiles, $item);
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
  background-image: url('img/background_01.jpg');  
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

#googleMap {
    height:300px;
}
  </style>
</head>
<body>

<div class="jumbotron bg-cover">
  <div class="overlay"></div>
  <div class="container">
  <h1>SaraFarm</h1>
  <p>The paradigm shift for food sovereignty by giving farmers the incentive for the data and sweat equity!</p>   
  </div>
</div>
  
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<a class="navbar-brand" href="/index.php">
    <img src="img/sarafarm.io.png" alt="Logo" style="width:40px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/reports.php">Reports</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="/crop_balances.php">Optimal Production</a>
      </li>	
      <li class="nav-item">
        <a class="nav-link" href="/">Help</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Logout</a>
      </li>		
      <li class="nav-item">
        <a class="nav-link" href="/profiles_kr.php">한국어</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section" id="profiles">
<div class="container mt-3">
<h2 class="pb-4">Profiles</h2>

<div class="row bg-info">
  <div class="col-sm-1">ID</div>
  <div class="col-sm-2">Person</div>
  <div class="col-sm-2">Phone</div>
  <div class="col-sm-3">Email</div>
  <div class="col-sm-4">Wallet</div>  
</div>

<?php
	foreach ($profiles as $index => $profile) {
		$membership = "&#9832;";
		switch ($profile->membership) {
			case 1 : $membership = "&#9773;"; break;
			case 2 : $membership = "&#9816;"; break;
			case 3 : $membership = "&#9818;"; break;
		}
?>		
<div class="row<?php echo ($index % 2) ? " bg-light" : ""; ?>" data-id="<?php echo $profile->id; ?>">
  <div class="col-sm-1"><?php echo $profile->id; ?></div>
  <div class="col-sm-2"><?php echo $membership; ?> <?php echo $profile->lastname; ?> <?php echo $profile->firstname; ?></div>
  <div class="col-sm-2"><?php echo $profile->phone; ?></div>
  <div class="col-sm-3 overflow-hidden"><?php echo $profile->email; ?></div>
  <div class="col-sm-4 overflow-hidden"><?php echo $profile->wallet; ?></div>  
</div>

<?php
	}
?>
<form id="profile" method="GET" action="profile_verification.php">
<input type="hidden" name="id" id="id" value="0">
</form>
</div>
</section>

<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">Copyright &copy;
    <a href="https://sarafarm.io">SaraFarm.io</a>
  </div>
</footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
	
$('#profiles .row').on('click', function () { 
	var form = $("#profile");
	var hidden = form.find("#id");
	
	if ($(this).data("id")) {
		hidden.val($(this).data("id"));
		console.log(hidden.val());
		form.submit();
	}
});
	
    </script>  
</body>
</html>
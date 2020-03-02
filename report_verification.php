<?php

$id = intval($_GET["id"]);
$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$reports = array();
$ids = array();

try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT id FROM report";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			array_push($ids, intval($row["id"]));
		}
	}
	
	$sql = "SELECT id, crop, estimatedAmountLastYear, calculatedAmountLastYear, estimatedAmountThisYear, calculatedAmountThisYear, ";
	$sql .= "currency, priceLastYear, seedling, harvest, lat, lng, area, ";
	$sql .= "unit, acreage, ownership, ownershipFrom, ownershipTo, ";
	$sql .= "estimatedFamilyLaborTime, calculatedFamilyLaborTime, estimatedHiredLaborTime, calculatedHiredLaborTime ";
	$sql .= "FROM report WHERE id=%d;";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->id = $row["id"];
			$item->crop = $row["crop"];			
			$item->estimatedAmountLastYear = intval($row["estimatedAmountLastYear"]);
			$item->calculatedAmountLastYear = intval($row["calculatedAmountLastYear"]);
			$item->estimatedAmountThisYear = intval($row["estimatedAmountThisYear"]);
			$item->calculatedAmountThisYear = intval($row["calculatedAmountThisYear"]);
			$item->currency = intval($row["currency"]); 
			$item->priceLastYear = intval($row["priceLastYear"]); 
			$item->seedling = trim($row["seedling"]); 
			$item->harvest = trim($row["harvest"]); 
			$item->lat = floatval($row["lat"]); 
			$item->lng = floatval($row["lng"]); 
			$item->area = trim($row["area"]); 
			$item->unit = intval($row["unit"]); 
			$item->acreage = intval($row["acreage"]); 
			$item->ownership = intval($row["ownership"]); 
			$item->ownershipFrom = trim($row["ownershipFrom"]); 
			$item->ownershipTo = trim($row["ownershipTo"]);
			$item->estimatedFamilyLaborTime = trim($row["estimatedFamilyLaborTime"]);
			$item->calculatedFamilyLaborTime = trim($row["calculatedFamilyLaborTime"]);
			$item->estimatedHiredLaborTime = trim($row["estimatedHiredLaborTime"]);
			$item->calculatedHiredLaborTime = trim($row["calculatedHiredLaborTime"]); 
			array_push($reports, $item);
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
        <a class="nav-link" href="/profiles.php">Profiles</a>
      </li>
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
        <a class="nav-link" href="/report_verification_kr.php?id=<?php echo $id; ?>">한국어</a>
      </li>		  
    </ul>
  </div>  
</nav>

<?php if (count($reports) > 0): ?>
<section class="container_section" id="cropdata">
<div class="container mt-3">
<div class="row pb-4">
  <div class="col-sm-3"><h2>Report</h2></div>
  <div class="col-sm-9">
  	<div class="btn-toolbar justify-content-end">
	<button type="button" class="btn mr-1 mb-1">Report ID:</button>
	<form id="profile" method="GET" action="report_verification.php">
    <select class="btn btn-outline-info mr-1 mb-1" id="id" name="id" onchange='if(this.value != 0) { this.form.submit(); }'>
	<?php 
		foreach ($ids as $index => $value) { 
			$selected = ($id == $value) ? "selected=\"selected\"" : "";
			$text = str_pad($value, 11, "0", STR_PAD_LEFT);
			$option = sprintf("<option value=\"%s\" %s>%s</option>\n", $value, $selected, $text);
	?>
    <?php echo $option ?>
	<?php } ?>
	</select>
	</form>
		<button type="button" class="btn btn-outline-info mr-1 mb-1">Still 6 data sheets are to verify.</button>
		<button type="button" class="btn btn-outline-success mr-1 mb-1">Next &raquo;</button>
	</div>
  </div>
</div>
<form id="report-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Crop</span>
    </div>
    <select class="form-control" id="crop" name="crop">
    <option value="1"<?php echo ($reports[0]->crop == 1) ? " selected=\"selected\"" : ""; ?>>RADISH</option>
    <option value="2"<?php echo ($reports[0]->crop == 2) ? " selected=\"selected\"" : ""; ?>>PEPPER</option>
    <option value="3"<?php echo ($reports[0]->crop == 3) ? " selected=\"selected\"" : ""; ?>>ONION</option>
    <option value="4"<?php echo ($reports[0]->crop == 4) ? " selected=\"selected\"" : ""; ?>>GARLIC</option>
	<option value="5"<?php echo ($reports[0]->crop == 5) ? " selected=\"selected\"" : ""; ?>>CABBAGE</option>
  </select>
	</div>
  
<fieldset class="form-group">
        <div class="row">
            <legend class="col-sm-3 col-form-label pt-0">Production data:</legend>
            <div class="col-sm-9">

  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Amount last year</span>
    </div>
	<select class="form-control" id="estimatedAmountLastYear" name="estimatedAmountLastYear">
    <option value="1"<?php echo ($reports[0]->estimatedAmountLastYear == 1) ? " selected=\"selected\"" : ""; ?>>0 - 100kg</option>
    <option value="2"<?php echo ($reports[0]->estimatedAmountLastYear == 2) ? " selected=\"selected\"" : ""; ?>>100 - 1000kg</option>
    <option value="3"<?php echo ($reports[0]->estimatedAmountLastYear == 3) ? " selected=\"selected\"" : ""; ?>>&gt; 1000kg</option>
	</select>
	<input type="text" class="form-control" placeholder="" id="calculatedAmountLastYear" name="calculatedAmountLastYear" value="<?php echo $reports[0]->calculatedAmountLastYear; ?>">
	</div>

  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Amount this year</span>
    </div>
	<select class="form-control" id="estimatedAmountThisYear" name="estimatedAmountThisYear">
    <option value="1"<?php echo ($reports[0]->estimatedAmountThisYear == 1) ? " selected=\"selected\"" : ""; ?>>0 - 100kg</option>
    <option value="2"<?php echo ($reports[0]->estimatedAmountThisYear == 2) ? " selected=\"selected\"" : ""; ?>>100 - 1000kg</option>
    <option value="3"<?php echo ($reports[0]->estimatedAmountThisYear == 3) ? " selected=\"selected\"" : ""; ?>>&gt; 1000kg</option>
	</select>
	<input type="text" class="form-control" placeholder="" id="calculatedAmountThisYear" name="calculatedAmountThisYear" value="<?php echo $reports[0]->calculatedAmountThisYear; ?>">	
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Price last year</span>
    </div>
    <select class="form-control" id="currency" name="currency">
    <option value="1"<?php echo ($reports[0]->currency == 1) ? " selected=\"selected\"" : ""; ?>>&#8361;</option>
    <option value="2"<?php echo ($reports[0]->currency == 2) ? " selected=\"selected\"" : ""; ?>>&#x24;</option>
    <option value="3"<?php echo ($reports[0]->currency == 3) ? " selected=\"selected\"" : ""; ?>>&euro;</option>
    </select>	
	<input type="text" class="form-control" placeholder="" id="priceLastYear" name="priceLastYear" value="<?php echo $reports[0]->priceLastYear; ?>">	
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Seedling</span>
    </div>
    <input type="date" class="form-control" id="seedling" name="seedling" value="<?php echo $reports[0]->seedling; ?>">
	</div>	
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Harvest</span>
    </div>
    <input type="date" class="form-control" id="harvest" name="harvest" value="<?php echo $reports[0]->harvest; ?>">
	</div>


            </div>
        </div>
    </fieldset>

<fieldset class="form-group">
        <div class="row">
            <legend class="col-sm-3 col-form-label pt-0">Land data:</legend>
            <div class="col-sm-9">
			<div class="mb-3">
				<div id="googleMap"></div>
			</div>

<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Location</span>
    </div>
    <input type="text" class="form-control" placeholder="lat" id="lat" name="lat" value="<?php echo $reports[0]->lat; ?>">
    <input type="text" class="form-control" placeholder="lng" id="lng" name="lng" value="<?php echo $reports[0]->lng; ?>">
  </div>
  
<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Area</span>
    </div>
    <input type="text" class="form-control" placeholder="Area" id="area" name="area" value="<?php echo $reports[0]->area; ?>">
  </div>
  
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Acreage</span>
    </div>
    <select class="form-control" id="unit" name="unit">
    <option value="1"<?php echo ($reports[0]->unit == 1) ? " selected=\"selected\"" : ""; ?>>sqm</option>
    <option value="2"<?php echo ($reports[0]->unit == 2) ? " selected=\"selected\"" : ""; ?>>pyong</option>
    <option value="3"<?php echo ($reports[0]->unit == 3) ? " selected=\"selected\"" : ""; ?>>are</option>
	<option value="4"<?php echo ($reports[0]->unit == 4) ? " selected=\"selected\"" : ""; ?>>ha</option>
    </select>
	<input type="text" class="form-control" placeholder="Size of growing area" id="acreage" name="acreage" value="<?php echo $reports[0]->acreage; ?>">	
	</div>  
    
		<div class="form-check">
			<label class="form-check-label">
				<input type="checkbox" class="form-check-input" value="1" id="ownership" name="ownership" <?php echo ($reports[0]->ownership) ? " checked=\"checked\"" : ""; ?>>Ownership
			</label>
		</div>		
		<div class="input-group mb-3">
			<div class="input-group-prepend">
			<span class="input-group-text">Period</span>
			</div>
			<input type="date" class="form-control" id="ownershipFrom" name="ownershipFrom" value="<?php echo $reports[0]->ownershipFrom; ?>">
			<input type="date" class="form-control" id="ownershipTo" name="ownershipTo" value="<?php echo $reports[0]->ownershipTo; ?>">
		</div>		

            </div>
        </div>
    </fieldset>

<fieldset class="form-group">
        <div class="row">
            <legend class="col-sm-3 col-form-label pt-0">Labor time data:</legend>
            <div class="col-sm-9">

		<div class="input-group mb-3">
			<div class="input-group-prepend">
			<span class="input-group-text">Family</span>
			</div>
			<select class="form-control" id="estimatedFamilyLaborTime" name="estimatedFamilyLaborTime">
			<option value="1"<?php echo ($reports[0]->estimatedFamilyLaborTime == 1) ? " selected=\"selected\"" : ""; ?>>0 - 100 Hours</option>
			<option value="2"<?php echo ($reports[0]->estimatedFamilyLaborTime == 2) ? " selected=\"selected\"" : ""; ?>>100 - 500 Hours</option>
			<option value="3"<?php echo ($reports[0]->estimatedFamilyLaborTime == 3) ? " selected=\"selected\"" : ""; ?>>500 - 1000 Hours</option>
			<option value="4"<?php echo ($reports[0]->estimatedFamilyLaborTime == 4) ? " selected=\"selected\"" : ""; ?>>1000 - 2000 Hours</option>
			<option value="5"<?php echo ($reports[0]->estimatedFamilyLaborTime == 5) ? " selected=\"selected\"" : ""; ?>>&gt; 2000 Hours</option>
			</select>
			<input type="text" class="form-control" id="calculatedFamilyLaborTime" name="calculatedFamilyLaborTime" value="<?php echo $reports[0]->calculatedFamilyLaborTime; ?>">
		</div>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">Hired</span>
				</div>
			<select class="form-control" id="estimatedHiredLaborTime" name="estimatedHiredLaborTime">
			<option value="1"<?php echo ($reports[0]->estimatedHiredLaborTime == 1) ? " selected=\"selected\"" : ""; ?>>0 - 100 Hours</option>
			<option value="2"<?php echo ($reports[0]->estimatedHiredLaborTime == 2) ? " selected=\"selected\"" : ""; ?>>100 - 500 Hours</option>
			<option value="3"<?php echo ($reports[0]->estimatedHiredLaborTime == 3) ? " selected=\"selected\"" : ""; ?>>500 - 1000 Hours</option>
			<option value="4"<?php echo ($reports[0]->estimatedHiredLaborTime == 4) ? " selected=\"selected\"" : ""; ?>>1000 - 2000 Hours</option>
			<option value="5"<?php echo ($reports[0]->estimatedHiredLaborTime == 5) ? " selected=\"selected\"" : ""; ?>>&gt; 2000 Hours</option>
			</select>			
			<input type="text" class="form-control" id="calculatedHiredLaborTime" name="calculatedHiredLaborTime" value="<?php echo $reports[0]->calculatedHiredLaborTime; ?>">
		</div>

            </div>
        </div>
    </fieldset>
  
  <div class="btn-group btn-block">
  <button type="button" class="btn btn-danger">Dismiss</button>
  <button type="button" class="btn btn-success">Verify</button>
  <button type="button" class="btn btn-warning">Review</button>
</div>

   </form>
</div>
</section>

<?php else: ?>
  <p>Default Content</p>
<?php endif; ?>

<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">Copyright &copy;
    <a href="https://sarafarm.io">SaraFarm.io</a>
  </div>
</footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwuO00NLmcsCVX-FVJuoUA7qjVYi4BXNs&callback=initMap" async defer></script>
  
<script>

var map, marker;

function initMap() {
	var myCenter = new google.maps.LatLng(<?php echo $reports[0]->lat; ?>, <?php echo $reports[0]->lng; ?>);
	var mapCanvas = document.getElementById("googleMap");
	var mapOptions = {
		center: myCenter, 
		zoom: 13,
		treetViewControl: false,
		mapTypeControl: false
	};

	map = new google.maps.Map(mapCanvas, mapOptions);
	marker = new google.maps.Marker(
		{
			position:myCenter,
			draggable: true
		}
	);
	marker.setMap(map);

	// Zoom to 9 when clicking on marker
	marker.addListener('click',function() {
		map.setZoom(9);
		map.setCenter(marker.getPosition());
	});

	map.addListener('click', function(event) {
		marker.setPosition(event.latLng);
		$('#lat').val(event.latLng.lat());
		$('#lng').val(event.latLng.lng());
		
		reverseGeocoding(event.latLng);
	});
}

function reverseGeocoding(latLng) {
	var location = latLng.lat() + "," + latLng.lng();
	var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + location + "&key=AIzaSyDwuO00NLmcsCVX-FVJuoUA7qjVYi4BXNs";
	$.getJSON(url, function (data) {
		for(var i=0; i < data.results.length; i++) {
			$('#area').val(data.results[i].formatted_address);
			break;
		 }
	});
}

function validateForm() {
	var name =  document.getElementById('name').value;
	if (name == "") {
		document.getElementById('status').innerHTML = "Complete the Name field!";
		return false;
	}
	var email =  document.getElementById('email').value;
	if (email == "") {
		document.getElementById('status').innerHTML = "Complete the email field!";
		return false;
	} else {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(!re.test(email)){
			document.getElementById('status').innerHTML = "Email format is invalid!";
			return false;
		}
	}
	var message =  document.getElementById('message').value;
	if (message == "") {
		document.getElementById('status').innerHTML = "Message cannot be empty!";
		return false;
	}
	var phone =  document.getElementById('phone').value;
	if (phone == "") {
		document.getElementById('status').innerHTML = "Phone cannot be empty!";
		return false;
	}	
    var answer =  document.getElementById('answer').value;
	if (answer == "") {
		document.getElementById('status').innerHTML = "Complete the answer field!";
		return false;
	}
  
    document.getElementById('status').innerHTML = "Sending...";

	var formData = {
		'module' : "contact",
		'name' : $('input[name=name]').val(),
		'email' : $('input[name=email]').val(),
		'message' : $('textarea[name=message]').val(),		
		'phone' : $('input[name=phone]').val(),		
		'date' : $('input[name=date]').val(),
		'question': $('input[name=question]').val(),		
		'answer'  : $('input[name=answer]').val()
	};

	$.ajax({
		url : "https://api.sarafarm.io",
		type: "POST",
		data : formData,
		dataType: "json",
		success: function(data, textStatus, xhr)
		{
			$('#status').text(data.status);
			
			if (data.status == 200) {
				$('#contact-form').closest('form').find("input[type=text], textarea").val("");
			}
		},
		error: function (xhr, textStatus, errorThrown)
		{
			console.log(xhr);console.log(textStatus);console.log(errorThrown);
			$('#status').text(xhr.responseText);
		}
	});	
}
    </script>  
</body>
</html>
<?php
session_start();

$id = intval($_GET["id"]);
$id = ($id == 0) ? intval($_SESSION["USERID"]) : $id;
$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$profiles = array();
	
try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT profile.id, membership, firstname, lastname, birthday, ";
	$sql .= "phone, lat, lng, area, address, zip, email, wallet, profile.erc20token ";
	$sql .= "FROM profile ";
	$sql .= "LEFT JOIN user ON (user.profile_id = profile.id) ";
	$sql .= "WHERE user.id=%d;";
	$sql = sprintf($sql, $id);
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->id = $row["id"];
			$item->membership = $row["membership"];			
			$item->firstname = trim($row["firstname"]);
			$item->lastname = trim($row["lastname"]);
			$item->birthday = trim($row["birthday"]);
			$item->phone = trim($row["phone"]);
			$item->lat = floatval($row["lat"]); 
			$item->lng = floatval($row["lng"]); 
			$item->area = trim($row["area"]); 
			$item->address = trim($row["address"]); 
			$item->zip = trim($row["zip"]);
			$item->email = trim($row["email"]);
			$item->wallet = trim($row["wallet"]);
			$item->erc20token = sprintf("%0.2f SFT", floatval($row["erc20token"]));
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
        <a class="nav-link" href="/report.php">Report</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="/crop_balance.php">Optimal Production</a>
      </li>	
      <li class="nav-item">
        <a class="nav-link" href="/">Help</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Logout</a>
      </li>		
      <li class="nav-item">
        <a class="nav-link" href="/profile_kr.php">한국어</a>
      </li>		  
    </ul>
  </div>  
</nav>

<?php if (count($profiles) > 0): ?>
<section class="container_section" id="profile">
<div class="container mt-3">
<div class="row pb-4">
  <div class="col-sm-3"><h2>Profile</h2></div>
  <div class="col-sm-9">

  </div>
</div>
<form id="profile-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" name="id" value="<?php echo $profiles[0]->id; ?>">
	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Member as</span>
    </div>
    <select class="form-control" id="membership" name="membership" disabled="disabled">
    <option value="1"<?php echo ($profiles[0]->membership == 1) ? " selected=\"selected\"" : ""; ?>>Farmer</option>
    <option value="2"<?php echo ($profiles[0]->membership == 2) ? " selected=\"selected\"" : ""; ?>>Validator</option>
	<option value="3"<?php echo ($profiles[0]->membership == 3) ? " selected=\"selected\"" : ""; ?>>Sarafarm foundation management</option>
  </select>
	</div>	
	
	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Person</span>
    </div>
    <input type="text" class="form-control" placeholder="First Name" value="<?php echo $profiles[0]->firstname; ?>">
    <input type="text" class="form-control" placeholder="Last Name" value="<?php echo $profiles[0]->lastname; ?>">
	</div>
   	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Birthday</span>
    </div>
    <input type="date" class="form-control" id="date" name="date" value="<?php echo $profiles[0]->birthday; ?>">
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Phone</span>
    </div>
    <input type="phone" class="form-control" id="date" id="phone" name="phone" value="<?php echo $profiles[0]->phone; ?>">
	</div>
	
	<div class="row">
		<div class="col-sm-2">
	  <label for="address">Home address:</label>
	  </div>
	  <div class="col-sm-10">
	  
			<div class="mb-3">
				<div id="googleMap"></div>
			</div>

<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Location</span>
    </div>
    <input type="text" class="form-control" placeholder="lat" id="lat" name="lat" value="<?php echo $profiles[0]->lat; ?>">
    <input type="text" class="form-control" placeholder="lng" id="lng" name="lng" value="<?php echo $profiles[0]->lng; ?>">
  </div>
  
<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Area</span>
    </div>
    <input type="text" class="form-control" placeholder="Area" id="area" name="area" value="<?php echo $profiles[0]->area; ?>">
  </div>	  
	  
		<div class="form-group">
		<textarea class="form-control" rows="5" required="" placeholder="222-4, Haeandong 1(il)-ga, Mokpo-si, Jeollanam-do" id="address" name="address">
<?php echo $profiles[0]->address; ?>
		</textarea></div>
	  </div>
	</div> 
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Zip code</span>
    </div>
    <input type="text" class="form-control" placeholder="000000" id="zip" name="zip" value="<?php echo $profiles[0]->zip; ?>">
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Email</span>
    </div>
    <input type="text" class="form-control" placeholder="eMail" id="email" name="email" value="<?php echo $profiles[0]->firstname; ?>">
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Wallet address</span>
    </div>
    <input type="text" class="form-control" placeholder="0x821e28109872cad442da8d8335be37d317d4f1e7" id="wallet" name="wallet" value="<?php echo $profiles[0]->wallet; ?>">
	</div>

  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Incentive</span>
    </div>
    <input type="text" class="form-control" placeholder="0.00 SFT" readonly="readonly" id="erc20token" name="erc20token" value="<?php echo $profiles[0]->erc20token; ?>">
	<div class="input-group-append">
    <button class="btn btn-success" type="button" id="reload">&#x21BB;</button>
	</div>	
	</div>
	
  <button type="submit" class="btn btn-primary btn-block">Submit</button>

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
  <script src="/js/web3.min.js"></script>
<script>
var map, marker;

function initMap() {
	var myCenter = new google.maps.LatLng(<?php echo doubleVal($profiles[0]->lat); ?>,<?php echo doubleVal($profiles[0]->lng); ?>);
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

function updateIncentive() {
	let tokenAddress = "0x3bb6e84638548ba1eC22b22f6bee3e2e8522b6cF";
	let walletAddress = $('#wallet').val();
	if(tokenAddress != "" && walletAddress != "") {
        getERC20TokenBalance(tokenAddress, walletAddress, (balance, symbol, name) => {
          $('#erc20token').val(balance.toString() + " " + symbol + " (" + name + ")");
        });        
    }
}

function getERC20TokenBalance(tokenAddress, walletAddress, callback) {
	// The minimum ABI to get ERC20 Token balance
	let minABI = [
	 // name
	 {
		"constant": true,
		"inputs": [],
		"name": "name",
		"outputs": [{"name": "","type": "string"}],
		"payable": false,
		"type": "function"
	  },	
	  // balanceOf
	  {
		"constant":true,
		"inputs":[{"name":"_owner","type":"address"}],
		"name":"balanceOf",
		"outputs":[{"name":"balance","type":"uint256"}],
		"type":"function"
	  },
	  // decimals
	  {
		"constant":true,
		"inputs":[],
		"name":"decimals",
		"outputs":[{"name":"","type":"uint8"}],
		"type":"function"
	  },
	  // symbol
	  {
		"constant": true,
		"inputs": [],
		"name": "symbol",
		"outputs": [{"name": "","type": "string"}],
		"type":"function"
	  }
	];
	
	// Get ERC20 Token contract instance
	let contract = web3.eth.contract(minABI).at(tokenAddress);
  
	// Call balanceOf function
	contract.balanceOf(walletAddress, (error, balance) => {
	  // Get decimals
	  contract.decimals((error, decimals) => {
		// Get symbol
		contract.symbol((error, symbol) => {
			// Get name
			contract.name((error, name) => {
				balance = balance.div(10**decimals);
				// call callback
				callback(balance, symbol, name);
			});
		});
	  });
	});	
}

$('#reload').on('click', function () {
    updateIncentive();
});

window.onload = function() {
  if (typeof web3 !== 'undefined') {
	web3 = new Web3(web3.currentProvider);
  } else {
	web3 = new Web3(new Web3.providers.HttpProvider("https://mainnet.infura.io"));
  }
  console.log(web3.version);
}
	
    </script>  
</body>
</html>
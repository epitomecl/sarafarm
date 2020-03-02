<?php 
session_start();

$userId = intval($_SESSION["USERID"]);

?>
<!DOCTYPE html>
<html lang="kr">
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
<a class="navbar-brand" href="/index_kr.php">
    <img src="img/sarafarm.io.png" alt="Logo" style="width:40px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/profile_kr.php">프로파일</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="/crop_balance_kr.php">개인별 적정 생산량</a>
      </li>		  
      <li class="nav-item">
        <a class="nav-link" href="/index_kr.php">안내</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout_kr.php">로그 아웃</a>
      </li>	 
      <li class="nav-item">
        <a class="nav-link" href="/report.php">English</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section" id="cropdata">
<div class="container mt-3">
<form id="report-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" name="userId" value="<?php echo $userId; ?>">
  <h2 class="pb-4">데이터 보고서</h2>

	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">농산물</span>
    </div>
    <select class="form-control" id="crop" name="crop">
    <option value="1">무</option>
    <option value="2">후추</option>
    <option value="3">양파</option>
    <option value="4">마늘</option>
	<option value="5">양배추</option>
  </select>
	</div>

<fieldset class="form-group">
        <div class="row">
            <legend class="col-sm-3 col-form-label pt-0">생산량:</legend>
            <div class="col-sm-9">

  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">작년 생산량</span>
    </div>
	<select class="form-control" id="estimatedAmountLastYear" name="estimatedAmountLastYear">
    <option value="1">0 - 100kg</option>
    <option value="2">100 - 1000kg</option>
    <option value="3">&gt; 1000kg</option>
	</select>
	<input type="text" class="form-control" placeholder="" id="calculatedAmountLastYear" name="calculatedAmountLastYear">
	</div>

  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">올해 생산량</span>
    </div>
	<select class="form-control" id="estimatedAmountThisYear" name="estimatedAmountThisYear">
    <option value="1">0 - 100kg</option>
    <option value="2">100 - 1000kg</option>
    <option value="3">&gt; 1000kg</option>
	</select>
	<input type="text" class="form-control" placeholder="" id="calculatedAmountThisYear" name="calculatedAmountThisYear">	
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">작년 가격</span>
    </div>
    <select class="form-control" id="currency" name="currency">
	<option value="1">&#8361;</option>
    <option value="2">&#x24;</option>
    <option value="3">&euro;</option>
    </select>
	<input type="text" class="form-control" placeholder="" id="priceLastYear" name="priceLastYear">	
	</div>
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">파종</span>
    </div>
    <input type="date" class="form-control" id="seedling" name="seedling">
	</div>	
	
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">수확기</span>
    </div>
    <input type="date" class="form-control" id="harvest" name="harvest">
	</div>


            </div>
        </div>
    </fieldset>

<fieldset class="form-group">
        <div class="row">
            <legend class="col-sm-3 col-form-label pt-0">토지 데이터:</legend>
            <div class="col-sm-9">
			<div class="mb-3">
				<div id="googleMap"></div>
			</div>
			
<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">위치</span>
    </div>
    <input type="text" class="form-control" placeholder="위도" id="lat" name="lat">
    <input type="text" class="form-control" placeholder="경도" id="lng" name="lng">
  </div>
  
<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">지역</span>
    </div>
    <input type="text" class="form-control" placeholder="지역" id="area" name="area">
  </div>
    
   	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">면적</span>
    </div>
    <select class="form-control" id="unit" name="unit">
    <option value="1">sqm</option>
    <option value="2">pyong</option>
    <option value="3">are</option>
	<option value="4">ha</option>
    </select>
	<input type="text" class="form-control" placeholder="100" id="acreage" name="acreage">	
	</div>  
	
		<div class="form-check">
			<label class="form-check-label">
				<input type="checkbox" class="form-check-input" value="1" id="ownership" name="ownership">경작지 소유 여부
			</label>
		</div>		
		<div class="input-group mb-3">
			<div class="input-group-prepend">
			<span class="input-group-text">기간</span>
			</div>
			<input type="date" class="form-control" id="ownershipFrom" name="ownershipFrom">
			<input type="date" class="form-control" id="ownershipTo" name="ownershipTo">
		</div>		

            </div>
        </div>
    </fieldset>

<fieldset class="form-group">
        <div class="row">
            <legend class="col-sm-3 col-form-label pt-0">노동 시간 데이터:</legend>
            <div class="col-sm-9">

		<div class="input-group mb-3">
			<div class="input-group-prepend">
			<span class="input-group-text">가족</span>
			</div>
			<select class="form-control" id="estimatedFamilyLaborTime" name="estimatedFamilyLaborTime">
			<option value="1">0 - 100 시간</option>
			<option value="2">100 - 500 시간</option>
			<option value="3">500 - 1000 시간</option>
			<option value="4">1000 - 2000 시간</option>
			<option value="5">&gt; 2000 시간</option>
			</select>
			<input type="text" class="form-control" id="calculatedFamilyLaborTime" name="calculatedFamilyLaborTime">
		</div>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">고용</span>
				</div>
			<select class="form-control" id="estimatedHiredLaborTime" name="estimatedHiredLaborTime">
			<option value="1">0 - 100 시간</option>
			<option value="2">100 - 500 시간</option>
			<option value="3">500 - 1000 시간</option>
			<option value="4">1000 - 2000 시간</option>
			<option value="5">&gt; 2000 시간</option>
			</select>			
			<input type="text" class="form-control" id="calculatedHiredLaborTime" name="calculatedHiredLaborTime">
		</div>

            </div>
        </div>
    </fieldset>
  
  <button type="submit" class="btn btn-primary btn-block">제출</button>

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
  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwuO00NLmcsCVX-FVJuoUA7qjVYi4BXNs&callback=initMap" async defer></script>
  
<script>

var map, marker;

function initMap() {
	var myCenter = new google.maps.LatLng(37.309207489212504,127.13085655285408);
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
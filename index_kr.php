<?php 

$cards = array("food_01.jpg", "food_02.jpg", "food_03.jpg", "food_04.jpg", "food_05.jpg", "food_06.jpg", "food_07.jpg");
mt_rand();
shuffle($cards);
array_splice($cards, 0, 0, "내일");
array_splice($cards, 2, 0, "뭐");
array_splice($cards, 4, 0, "드실");
array_splice($cards, 7, 0, "거에요");
array_splice($cards, 9, 0, "?");

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
  .carousel-inner{overflow:visible}  
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
  .carousel-item {
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
.bg-sarafarm {
	background-color: #00FCFF;
}
.card-title {
	font-size:2.5vw;
	z-index: 1;
	text-shadow: 2px 2px #CECECE;	
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
<a class="navbar-brand" href="index_kr.php">
    <img src="img/saraeat.png" alt="Logo" style="width:40px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
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
        <a class="nav-link" href="/index.php">English</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section">
	<div class="container shuffle">
	<?php for ($offset = 0; $offset <= 1; $offset++): ?>
		<div class="row mb-0">
		<?php foreach (array_slice($cards, $offset * 6, 6) as $index => $text): ?>
			<div class="col-2 p-1">
				<div class="card bg-sarafarm h-100">
				<?php if (strpos($text, ".jpg") === false): ?>
					<div class="card-body p-0 align-items-center d-flex justify-content-center">
						<h4 class="card-title"><?php echo $text; ?></h4>
					</div>
				<?php else: ?>
					<div class="card-body p-0 align-items-center d-flex justify-content-center">
						<img src="img/<?php echo $text; ?>" class="img-fluid rounded">
					</div>
				<?php endif; ?> 
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	<?php endfor; ?>
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
/**
 * Randomize array element order in-place.
 * Using Durstenfeld shuffle algorithm.
 */
function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
}

function updateBoard(path) {
	var images = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16"];
	
	shuffleArray(images);
	
	$(path + ' img').each(function(index) {
		$(this).prop('src', "img/food_"+images[index]+".jpg");
    });
}

$(document).ready(function() {
	setInterval('updateBoard(".shuffle")', 3000);
});

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
			$('#status').text(mapping(data.status));
			$('#contact-form').closest('form').find("input[type=text], input[type=tel], input[type=date], textarea").val("");
		},
		error: function (xhr, textStatus, errorThrown)
		{
			$('#status').text(mapping(xhr.status));
		}
	});	
}

function mapping(status) {
	switch(status) {
		case 200: 
			return "Your message has been sent and the form has been reset.";
		default:
			return "Please fill in the fields correctly.";
	}
}
    </script>  
</body>
</html>

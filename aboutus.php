<?php $answer = "12 * 12 ="; 
$anti = array(
	"12 * 12 =" => "144",
	"11 * 11 =" => "121",
	"10 * 11 =" => "110",
	"144 - 12 =" => "132",
	"512 / 4 =" => "128",
	"1024 - 512 =" => "512",
	"27 * 6 =" =>  "162");
$keys = array_keys($anti);
$index = rand(0, count($keys) - 1);
$question = $keys[$index];
$answer = $anti[$question];

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
	background-color: #9FE59D;
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
<a class="navbar-brand" href="index.php">
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
      <li class="nav-item">
        <a class="nav-link" href="/veggie.php">Vegan Weekly Plan</a>
      </li>	
      <li class="nav-item active">
        <a class="nav-link" href="/aboutus.php">About us</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="#contact">Contact</a>
      </li>	  
      <li class="nav-item">
        <a class="nav-link" href="/aboutus_kr.php">한국어</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section">
<div class="container">
  <div class="row">
    <div class="col-sm-3"><img src="img/sarafarm.io.png" class="img-fluid"></div>
    <div class="col-sm-9 mx-auto text-justify">
	<p>
	Hello and welcome to SaraEat. 
	</p>
	<p>
	I'm Sarah, your Pescarian Vegetarian Counselor.  
	Being a vegetarian is not so difficult with SaraEat. 
	Many people choose vegetarianism in many different ways and reasons, but while they choose vegetarianism, they have a lot of anxiety and worry about their nutrition. 
	However, being a vegetarian and eating a fish and seafood pescatarian is the way to enjoy the longest and most healthy diet and to get protein, omega 3 and quality nutrients. 
	</p>
	<p>
	By the way, as with all other meals, the most important dietary secret is to eat seafood with a variety of natural foods, including dark green and yellow fruits and vegetables, leafy vegetables, nuts, seeds, and seaweed. 
	</p>
	<p>
	However, there are certain foods to avoid, for example, foods that contain a lot of sugar including  fruits, and foods that are high in starch and carbohydrates.
	</p>
	<h2>Why SaraEat?</h2>
	<p>
	SaraEat is a vegetarian platform that provides a data-driven application that takes advantage of interactive and gamification features. 
	SaraEat provides options for vegan menus and nutrition metrics for calories. 
	It also provides quantitative information about nutrients that are easily deficient in vegetarians such as proteins and omega3.
	</p>
	<h4>(1) Data Driven Interactive Nutrients Recommendation Algorithm</h4>
	<p>
	The SaraFarm vegan application provides an intuitive, interactive UI for food nutrition data. 
	It automatically calculates the nutrients for your food menu and we advise on missing or overdose.
	</p>
	<h4>(2) Diet Management On & Off Content Platform</h4>
	<p>The SaraFarm vegan platform allows users to manage their daily diet and rewards various participation including on and off community activities such as uploading recipes and new vegan restaurants. 
	</p>
	<h4>(3) Globalization of Korean Vegan Food</h4>
	<p>We develop new Korean vegan menus, and manage the data metrics for new vegan food resources, 
	reward the customers' Korean vegan recipes. 
	SaraFarm will contribute to the global marketing and promotion of Korean vegan food.
	</p>
    </div>
  </div>
</div>
</section>

<section class="container_section bg-light" id="team">
<div class="container">
  <h2>Our amazing team</h2>
  <p>SaraFarm started as an intra EpitomeCL’s startup to solve the agricultural industry’s problem using blockchain technology and consists of blockchain professionals and agricultural supply chain specialist.</p>
<div class="card-deck">
    <div class="card">
      <img src="img/team_01.jpg" alt="BB Kang" style="width:100%">
      <div class="container">
        <h3>BB Kang</h3>
        <p class="title">Founder & CEO</p>
        <p>BB is a Strategist & Food scientist</br> 
Token economy architect</br> 
CKO, EpitomeCL</br> 
Master of future Strategies program, KAIST</br> 
MBA,George Washington University</br> 
BBA, Business Administration, Yonsei University </br> 
BS, Food Engineering, Yonsei University
		</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_02.jpg" alt="Marian Kulisch" style="width:100%">
      <div class="container">
        <h3>Marian Kulisch</h3>
        <p class="title">Cofounder & Blockchain SW Engineer</p>
        <p>Marian is a experienced software developer (android, php, c#). 
		He worked as Java android developer for Knowre. 
		Now he works as blockchain developer for EpitomeCL as well. 
		</p>
		<p>Beuth University of Applied Sciences Berlin.</br>
		University of Potsdam.</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_03.jpg" alt="Social innovator" style="width:100%">
      <div class="container">
        <h3>Social Innovator</h3>
        <p class="title">Growth hacker</p>
        <p>Qualifications: More than 1 year of experience in data science, software engineering and e-commerce.
		Excellent document creation and communication.</p>
      </div>
    </div>	
</div>
</div>
</section>

<section class="container_section" id="contact">
<div class="container">
<h2>Contact</h2>
  <p>
	Please tell us about your ideas.  One of our food consultants will respond shortly.
	<b class="status" id="status"></b>
  </p>
<form id="contact-form" action="/">
<div class="row">
  <div class="col-sm-6">
	<div class="form-group"><input type="text" required="" placeholder="Name" class="form-control" id="name" name="name"></div>
  </div>
  <div class="col-sm-6">
	<div class="form-group"><input type="text" required="" placeholder="eMail" class="form-control" id="email" name="email"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
	<div class="form-group"><textarea class="form-control" rows="5" required="" placeholder="Message" id="message" name="message"></textarea></div>
  </div>
</div>  
<div class="row">
  <div class="col-sm-6">
	<div class="form-group"><input type="tel" placeholder="Phone" class="form-control" id="phone" name="phone"></div>
  </div>
  <div class="col-sm-6">
	<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Date</span>
      </div>
    <input type="date" class="form-control" id="date" name="date">
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
   <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text"><?php echo $question; ?></span>
    </div>
    <input type="text" class="form-control" placeholder="Answer" id="answer" name="answer">
	<input type="hidden" value="<?php echo $question; ?>" id="question" name="question">
  </div>
  </div>
</div>
<button type="button" class="btn btn-primary btn-block" onclick="validateForm()">Submit</button>
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

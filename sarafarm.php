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
<a class="navbar-brand" href="#">
    <img src="img/sarafarm.io.png" alt="Logo" style="width:40px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/login.php">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#team">Team</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="#contact">Data</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/registration.php">Registration</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/sarafarm_kr.php">한국어</a>
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
	Sara Farm project is summarised as the farming data market place between data buyers and data providers based on the blockchain technology and token economy.
But it is initiated to solve the persistent problem of agriculture industry, which are crop price volatility and farming data integrity.
Sara farm can be 4P smart farm innovator and paradigm shift for data management and utilization system.
      </p>
	  <p>Why 4P</p>
	  <ol>
	    <li>Participate: Farmers proactively participate with our incentive system instead of responding</li>
	    <li>Predict: In Sara Farm platform, we can predict the total production based on the gathered data from farmers</li>
		<li>Personalize: we customize the production based on the total collected data and individual farmers data and reward and motivate</li>
		<li>Prevent: Different from crop insurance, we take the preventive measure for crop price stability based on the data management and token economy</li>
		</ol>
    </div>
  </div>
</div>
</section>

<section class="container_section">
<div class="container">
<div id="demo" class="carousel slide carousel-fade" data-ride="carousel">
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
  </ul>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/carousel_01.jpg" alt="What has your organisation enabled so far in quantified terms?" width="1100" height="500">
      <div class="carousel-caption">
        <h5><span class="question-icon">Q: </span>What has your organisation enabled so far in quantified terms?</h5>
        <p>As quantified terms (e.g. 20% efficiency gain, 100 beneficiaries received aid in the past six months) for instance... We have presented our ideas in 10,000 members of Korean Seoul Ethereum community and 5,000 members of Berlin Ethereum community and 1 pilot test in 1 agritech company.</p>
      </div>   
    </div>
    <div class="carousel-item">
      <img src="img/carousel_02.jpg" alt="What positive social or environmental do you aim to generate?" width="1100" height="500">
      <div class="carousel-caption">
        <h5><span class="question-icon">Q: </span>What positive social or environmental do you aim to generate?</h5>
        <p> 
		<ol>
		  <li>Crop price volatility and poverty of small scale farmers</li>
		  <li>Decreasing the economic gap and digital divide</li>
		  <li>Farmers Data ownership</li>
		  <li>Food Security</li>
		</ol>
      </div>   
    </div>
    <div class="carousel-item">
      <img src="img/carousel_03.jpg" alt="Why are you specifically using blockchain in your project?" width="1100" height="500">
      <div class="carousel-caption">
        <h5><span class="question-icon">Q: </span>Why are you specifically using blockchain in your project?</h5>
        <p>
		<ol>
		  <li>Data integrity of farming industry by validators and consensus algorithms</li>
		  <li>Data ownership and Incentive design mechanism</li>
		  <li>End-to-end crop data traceability</li>
		</ol>
		</p>
      </div>   
    </div>
  </div>
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
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
        <p>BB is a strategist,  future scientist, and token economy architect. She works as CKO for EpitomeCL as well. 
		Master of future Strategies program, KAIST. MBA,George Washington University
		</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_02.jpg" alt="Marian Kulisch" style="width:100%">
      <div class="container">
        <h3>Marian Kulisch</h3>
        <p class="title">Cofounder & Blockchain SW Engineer</p>
        <p>Marian is a experienced software developer (android, php, c#). He worked as Java android developer for Knowre. 
		Now he works as blockchain developer for EpitomeCL as well. Beuth University of Applied Sciences Berlin. University of Potsdam.</p>
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
<h2>Contact / Crop Data</h2>
  <p>
	Please tell us about your crops and production schedule.  One of our farming consultants will respond shortly.
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

<section class="container_section bg-light" id="partner">
<div class="container">
  <h2>Our partnership sites</h2>
<div class="card-deck">
    <div class="card bg-light text-dark btn-outline-success">
		<div class="card-body">
			<h4 class="card-title">EPINET</h4>
			<p class="card-text">EPINET cares for the environment</p>
			<a href="http://www.epinet.co.kr/" class="btn btn-primary">epinet.co.kr</a>
		</div>
    </div>
    <div class="card bg-light text-dark btn-outline-success">
		<div class="card-body">
			<h4 class="card-title">SUGEUP</h4>
			<p class="card-text">Comprehensive Agricultural Products Distribution Information System</p>
			<a href="https://sugeup.or.kr/stats/dailySnd.do" class="btn btn-primary">sugeup.or.kr</a>
		</div>
    </div>	
    <div class="card bg-light text-dark btn-outline-success">
		<div class="card-body">
			<h4 class="card-title">POSITIVEBLOCKCHAIN</h4>
			<p class="card-text">Real Impact. Real Use cases.</p>
			<a href="https://positiveblockchain.io" class="btn btn-primary">positiveblockchain.io</a>
		</div>
    </div>		
    <div class="card bg-light text-dark btn-outline-success">
		<div class="card-body">
			<h4 class="card-title">NONGUB</h4>
			<p class="card-text">Gyeonggi-do Agricultural Research and Extension Services</p>
			<a href="https://nongup.gg.go.kr/" class="btn btn-primary">nongup.gg.go.kr</a>
		</div>
    </div>	
</div>
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

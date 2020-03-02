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
<html lang="ko">
<head>
  <title>사라팜</title>
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
  <h1>사라팜</h1>
  <p>블록체인 및 토큰이코노미 기반 4P 농업혁신 프로젝트!</p>   
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
        <a class="nav-link" href="/login.php">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/index.php#team">Team</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="/index.php#contact">Data</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/registration_kr.php">한국어</a>
      </li>	  
    </ul>
  </div>  
</nav>

<section class="testimonial py-5" id="testimonial">
    <div class="container">
        <div class="row ">
            <div class="col-md-4 py-5 bg-info text-white text-center ">
                <div class=" ">
                    <div class="card-body">
                        <img src="img/sarafarm.io.png" style="width:50%">
                        <h2 class="py-3">Registration</h2>
                        <p>With your participation, you contibute to improve the problem solving of crop price volatility and farming data integrity.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 py-5 border">
                <h4 class="pb-4">Please fill out with your details.</h4>
<form id="registration-form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

    <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Person</span>
    </div>
    <input type="text" class="form-control" placeholder="First Name">
    <input type="text" class="form-control" placeholder="Last Name">
	</div>

  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Email</span>
    </div>
    <input type="text" class="form-control" placeholder="aa@bb.cc" id="email" name="email">
	</div>
					
  	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Wallet address</span>
    </div>
    <input type="text" class="form-control" placeholder="0x821e28109872cad442da8d8335be37d317d4f1e7" id="wallet" name="wallet">
	</div>	

	<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Member as</span>
    </div>
    <select class="form-control" id="membership" name="membership">
    <option value="1">Farmer</option>
    <option value="2">Validator</option>
	<option value="3" disabled="disabled">Sarafarm foundation management</option>
  </select>
	</div>	
					
						<div class="input-group mb-3">
                                 <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                  <label class="form-check-label" for="invalidCheck2">
                                    <small>By clicking Submit, you agree to our Terms & Conditions, Visitor Agreement and Privacy Policy.</small>
                                  </label>
                                </div>
						</div>
                    
   <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text"><?php echo $question; ?></span>
    </div>
    <input type="text" class="form-control" placeholder="Answer" id="answer" name="answer">
	<input type="hidden" value="<?php echo $question; ?>" id="question" name="question">
  </div>					
					
  <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
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
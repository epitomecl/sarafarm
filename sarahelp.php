<!DOCTYPE html>
<html lang="en">
<head>
  <title>내일 뭐 드실 거에요?</title>
  <link rel="shortcut icon" href="https://sarafarm.io/img/sarafarm.io.ico" />  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
  <style>
  
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
.container_section {
    padding-top: 2rem;	
    padding-bottom: 2rem;
}
.status {
	color: red;
	font-weight: bold;
}
.cursor-pointer {
	cursor: pointer;
}
.bg-breakfast {
	background-color: #BBE1BB;
}
.bg-lunch {
	background-color: #8AFC8A;
}
.bg-dinner {
	background-color: #8198FF;
}
.bg-snack {
	background-color: #63BCBC;
}
.bg-total {
	background-color: #CEB08B;
}
  </style>
</head>
<body>

<div class="jumbotron bg-cover">
  <div class="overlay"></div>
  <div class="container">
  <h1>Sarafarm Veggie</h1>
  <p>The paradigm shift for vegetarian food!</p>
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
        <a class="nav-link" href="/foodmenu.php">SaraGame</a>
      </li>	
      <li class="nav-item">
        <a class="nav-link" href="/sarahelp_kr.php">한국어</a>
      </li>	  
    </ul>
  </div>  
</nav>

<section class="container_section bg-light">
	<div class="container-fluid">
		<div class="row mb-4">
			<div class="col">Hello, This is Sara. Let’s get started. What do you eat for tomorrow?</div>
		</div>	
		<div class="embed-responsive embed-responsive-16by9">
			<video playsinline="playsinline" autoplay="autoplay" loop="loop">
				<source src="media/saraeat720.mp4" type="video/mp4">
			</video>
		</div>
	</div>
</div>

<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">Copyright &copy;
    <a href="https://sarafarm.io">Sarafarm.io</a>
  </div>
</footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript">

</script>
</body>
</html>

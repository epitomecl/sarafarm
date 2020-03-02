<?php

$module = trim(isset($_POST["module"]) ? $_POST["module"] : "");
$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$id = 0;
$membership = 0;
	
if (strcmp($module, "login") == 0) {
	$email = trim($_POST["email"]);
	$password = trim($_POST["password"]);
	
	try {
		if ($mysqli->connect_error) {
			throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
		}
		$mysqli->set_charset("utf8");
		
		$sha256 = "";
		$sql = "SELECT salt FROM user LEFT JOIN profile ON (profile.id = user.profile_id) WHERE email='%s';";
		$sql = sprintf($sql, $mysqli->real_escape_string($email));
		$result = $mysqli->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$salt = trim($row["salt"]);
				$sha256 = hash_hmac("sha256", $password, $salt);
			}
		}
		
		$sql = "SELECT user.id, profile_id, membership FROM user LEFT JOIN profile ON (profile.id = user.profile_id) WHERE email='%s' AND password='%s';";
		$sql = sprintf($sql, $mysqli->real_escape_string($email), $sha256);
		$result = $mysqli->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$id = intval($row["id"]);
				$membership = intval($row["membership"]);	
			}
		}
		
		if ($id > 0) {
			session_start();
            $_SESSION["USERID"] = $id;
			
			if ($membership == 1) {
				header("location:profile_kr.php");
			} else {
				header("location:profiles_kr.php");				
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
}
?>
<!DOCTYPE html>
<html lang="kr">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Sarafarm.io</title>
  <link rel="shortcut icon" href="https://sarafarm.io/img/sarafarm.io.ico" />
  
  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="css/coming-soon.min.css" rel="stylesheet">

</head>

<body>

  <div class="overlay"></div>
  <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="https://media.giphy.com/media/RrU8f9lImvJja/giphy.mp4" keep="https://media.giphy.com/media/Slh0ygCFUyb04/giphy.mp4" old="mp4/bg.mp4" type="video/mp4">
  </video>

  <div class="masthead">
    <div class="masthead-bg"></div>
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-12 my-auto">
          <div class="masthead-content text-white py-5 py-md-0">
			<img src="img/sarafarm.io.png" alt="SaraFarm.io">
            <h1 class="mb-3">사라팜</h1>
            <p class="mb-5">블록체인 및 토큰이코노미 기반 4P 농업혁신 프로젝트!</p>
			<audio autoplay="autoplay">
				<source src="/media/schumann-op68-frohlicher-landmann.mp3" type="audio/mpeg">
			</audio>
			<form id="login-form" method="post" action="login_kr.php">
				<input type="hidden" name="module" value="login">					
			<div class="input-group input-group-login">
              <input type="email" name="email" class="form-control" placeholder="이메일을 입력...">
			  <input type="password" name="password" class="form-control" placeholder="암호를 입력..." minlength="4" required="required">
              <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">로그인</button>
              </div>
            </div>
			</form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="social-icons">
    <ul class="list-unstyled text-center mb-0">
      <li class="list-unstyled-item">
        <a href="/">
          <i class="far fa-times-circle"></i>
        </a>
      </li>
    </ul>
  </div>
	
  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Custom scripts for this template -->
  <script src="js/coming-soon.min.js"></script>

</body>

</html>

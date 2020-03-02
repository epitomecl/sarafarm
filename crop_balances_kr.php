<?php

$id = intval($_GET["id"]);
$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$reports = array();
	
try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT report.id, firstname, lastname, crop, report.area, ";
	$sql .= "calculatedAmountLastYear, calculatedAmountThisYear, production, ";
	$sql .= "(calculatedAmountThisYear * (100 - production) / 100) AS aiBalanceAdjustment, ";
	$sql .= "(((calculatedAmountLastYear + calculatedAmountThisYear) / 2) * (100 - production) / 100) AS sarafarmAdjustment ";
	$sql .= "FROM report ";
	$sql .= "LEFT JOIN prediction ON (prediction.report_id = report.id) ";	
	$sql .= "LEFT JOIN user ON (user.id = report.user_id) ";
	$sql .= "LEFT JOIN profile ON (profile.id = user.profile_id) ";
	$sql .= ($id > 0) ? sprintf("WHERE report.user_id = %d;", $id) : ";";
	
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->id = $row["id"];
			$item->firstname = trim($row["firstname"]);
			$item->lastname = trim($row["lastname"]);
			$item->crop = intval($row["crop"]);
			$item->area = trim($row["area"]);			
			$item->calculatedAmountThisYear = intval($row["calculatedAmountThisYear"]);
			$item->aiBalanceAdjustment = intval($row["aiBalanceAdjustment"]);
			$item->sarafarmAdjustment = intval($row["sarafarmAdjustment"]);	
			$item->success = 0;
			
			if ($item->calculatedAmountThisYear >=	$item->aiBalanceAdjustment && $item->calculatedAmountThisYear <= $item->sarafarmAdjustment) {
				$item->success = 1;
			}
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
  <h1>사라팜</h1>
  <p>블록체인 및 토큰이코노미 기반 4P 농업혁신 프로젝트</p>   
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
        <a class="nav-link" href="/profiles_kr.php">리스팅 프로파일</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/reports_kr.php">리스팅 데이터 보고서</a>
      </li>	  
      <li class="nav-item">
        <a class="nav-link" href="/index_kr.php">안내</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout_kr.php">로그 아웃</a>
      </li>		
      <li class="nav-item">
        <a class="nav-link" href="/crop_balances.php">English</a>
      </li>		  
    </ul>
  </div>  
</nav>

<section class="container_section" id="reports">
<div class="container mt-3">
<div class="row pb-4">
  <div class="col-sm-6"><h2>개인별 적정 생산량</h2></div>
  <div class="col-sm-6">
  	<div class="btn-toolbar justify-content-end">
		<a href="reports_csv.php" class="btn btn-outline-success mr-1 mb-1">CSV 내보내기</a>
	</div>
  </div>
</div>

<div class="row bg-info">
  <div class="col-sm-1">ID</div>
  <div class="col-sm-2">성함</div>
  <div class="col-sm-2">지역</div> 
  <div class="col-sm-1">농산물</div>
  <div class="col-sm-2">올해 생산량 (kg)</div>  
  <div class="col-sm-2">AI 조정 (kg)</div>
  <div class="col-sm-2">사라팜 조정 (kg)</div>
</div>

<?php
	foreach ($reports as $index => $report) {
		$crop = "&#9910;";
		switch ($report->crop) {
			case 1 : $crop = "Radish"; break;
			case 2 : $crop = "Pepper"; break;
			case 3 : $crop = "Onion"; break;
			case 2 : $crop = "Garlic"; break;
			case 3 : $crop = "Cabbage"; break;			
		}

		$bgColor = ($index % 2) ? " bg-light" : "";
		$bgColor = ($report->success) ? " bg-warning" : $bgColor;
?>	
<div class="row<?php echo $bgColor; ?>" data-id="<?php echo $report->id; ?>">
  <div class="col-sm-1"><?php echo $report->id; ?></div>
  <div class="col-sm-2"><?php echo $report->lastname; ?> <?php echo $report->firstname; ?></div>
  <div class="col-sm-2"><?php echo $report->area; ?></div>  
  <div class="col-sm-1"><?php echo $crop; ?></div>
  <div class="col-sm-2"><?php echo $report->calculatedAmountThisYear; ?></div>
  <div class="col-sm-2"><?php echo $report->aiBalanceAdjustment; ?></div>  
  <div class="col-sm-2"><?php echo $report->sarafarmAdjustment; ?></div>   
</div>

<?php
	}
?>
<form id="report" method="GET" action="report_verification_kr.php">
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

$('#reports .row').on('click', function () { 
	var form = $("#report");
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
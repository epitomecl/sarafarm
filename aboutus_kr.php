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
      <li class="nav-item active">
        <a class="nav-link" href="/aboutus_kr.php">About us</a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="#contact">Contact</a>
      </li>	  
      <li class="nav-item">
        <a class="nav-link" href="/aboutus.php">English</a>
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
		안녕하세요, 사라잇에 오신 것을 환영합니다. 저는 여러분의 페스카테리언 채식주의 카운셀러 사라입니다. 
		</p>
		<p>
		사라잇과 함께라면 채식주의자가 되는 것이 그리 어렵지 않습니다. 
		많은 사람들이 여러 경로와 이유에서 채식을 선택하지만 채식을 선택하면서도 단백질 등 영양 섭취에 대한 많은 불안과 걱정을 하게 됩니다.
		그러나 채식주의 중에서도 생선과 해산물을 섭취하는 페스카테리언이 되는 것은 가장 오래 지속할 수 있는 건강한 식생활을 향유하게 되는 길이며 단백질 및 오메가 3 및 양질의 영양소를 섭취할 수 있는 길입니다. 
		그런데 다른 모든 식사에서와 마찬가지로 가장 중요한 식생활 비결은 진한 녹황색 과일과 채소, 잎이 많은 채소, 견과류, 씨앗, 해조류 등 다양한 자연에서 나오는 음식과 함께 해산물을 먹는 것입니다.
		</p>
		<p>
		그러나 반드시 제한할 음식이 있는데 과일을 포함하여 당(sugar)을 많이 함유하고 있는 식품과 전분과 탄수화물이 많은 음식 섭취를 제한하는 것입니다. 
		</p>
		<h2>왜 사라잇 인가?</h2>
		<p>
		사라잇은 상호작용 게이미피케이션 기능을 활용한 식단제공 어플리케이션으로서 채식 
		메뉴에 대한 선택지를 제공하고 사용자가 선택한 메뉴에 대한 영양소 (탄수화물, 단백
		질, 지방 함량 및 열량, 비타민, 미네랄) 정보 및 채식에서 결핍되기 쉬운 영양소에 대한 정량적 정보를 대화 형식으로 제공합니다.
		</p>
		<h4>(1) 데이터 기반 인터랙티브 영양소 추천 알고리즘</h4>
		<p>
		사라팜 비건 어플리케이션은 식품의 영양 데이터에 대한 직관적이면서도 인터랙티브한 UI, 
		UX 를 통해 사용자가 선택한 음식 메뉴에 대한 영양소를 자동적으로 계산해주고 
		부족하거나 과잉 섭취한 부분에 대한 조언을 해줍니다. 
		</p>
		<h4>(2) 식생활 관리 온&오프 콘텐츠 플랫폼</h4>
		사라팜 비건 플랫폼에서는 사용자가 매일의 식생활에 대한 데이터 기반 관리 
		및 온오프 커뮤니티 일기를 기록하는 다양한 참여에 대한 보상을 함으로써 
		플랫폼 참여와 지속가능한 이코노미 생태계를 구현함.
		</p>
		<h4>(3) 한식 비건의 세계화</h4>
		<p>
		사라팜 비건 한식 플랫폼에서는 새로운 한식 비건 메뉴를 개발하고 영양소 데이터를 
		관리하며 고객들의 레시피에 대한 보상을 하고 비건 레스토랑과 제휴를 통해 한식 
		비건을 전사회적으로 보급하고 세계화하는 데 기여할 것임. 
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
        <h3>강보영(BB Kang)</h3>
        <p class="title">사라팜 대표창업자</p>
        <p> 
사라팜 대표 창업자</br> 
사라팜 푸드테크 컨설턴트</br> 
전략 및 경영 컨설턴트 </br> 
에피토미씨엘 CKO</br> 
서울시모바일 도시재생프로젝트 PM</br> 
신용보증기금, 한국체육공단 </br> 
중장기전략 경영컨설턴트</br> 
동서증권 애널리스트</br> 
카이스트 미래전략대학원 석사</br> 
미국 조지워싱턴 MBA</br> 
연세대 경영학과 졸업</br> 
연세대 식품공학과 졸업
		</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_02.jpg" alt="Marian Kulisch" style="width:100%">
      <div class="container">
        <h3>Marian Kulisch</h3>
        <p class="title">사라팜 공동창업자</p>
        <p>
사라팜 블록체인 개발자</br>
에피토미씨엘 블록체인개발자</br>
Knowre 안드로이드 자바개발자</br>
베를린 보이트기술대학교 미디어정보학
</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_03.jpg" alt="Social innovator" style="width:100%">
      <div class="container">
        <h3>Social Innovator</h3>
        <p class="title">사회혁신가</p>
        <p>자격요건: 데이터사이언스, 소프트웨어 공학, 전자성거래 경력 1년 이상</p>
		<p>뛰어난 문서 작성 및 커뮤니케이션 능력</p>
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

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
  <p>블록체인 및 토큰이코노미 기반 4P 농업혁신 프로젝트</p>   
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
        <a class="nav-link" href="/login_kr.php">로그인</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#team">팀</a>
      </li>   
      <li class="nav-item">
        <a class="nav-link" href="#contact">데이터</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/registration_kr.php">등록</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/sarafarm.php">English</a>
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
	사라팜 프로젝트는 블록체인 기술과 토큰이코노미를 기반으로 한 데이터 구매자와 데이터 공급자 간의 농업 데이터 마켓플레이스입니다. 
	사라팜 프로젝트는 한국 농업의 고질적인 농작물의 수급불안정과 가격 변동성 문제를 해결하고 농업 데이터 무결성을 제고하기 위해 시작되었습니다. 
	사라팜은 혁신적인 농업 데이터 관리 및 활용을 위한 4P 스마트팜 패러다임 혁신을 제시합니다.
      </p>
	  <p>그렇다면 사라팜이 제시하는 4P는 무엇일까요?</p>
	  <ol>
	    <li>Participate(참여): 농부들은 농업데이터 수집에 대해서 단지 수동적으로 대응하는 대신 사라팜 토큰 인센티브시스템을 통해 데이터 수집 및 활용에 적극적으로 참여하게 됩니다.</li>
	    <li>Predict(예측):  사라팜 플랫폼에서는 농부가 수집한 데이터를 기반으로 각 작물에 대한 생산량을 예측할 수 있습니다.</li>
		<li>Personalize(맞춤화): 사라팜 플랫폼에서는 수집된 농업 데이터를 바탕으로 농부의 과거 생산량과 경작지 면적 등을 기준으로 농부 개인별 적정 생산량 데이터를 계산하여 제시하며 이에 따른 보상 메커니즘을 제공합니다.</li>
		<li>Prevent(예방) : 사라팜은 농업의 재배 보험의 사후적 방법과는 달리, 데이터 과학 및 토큰 경제를 기반으로 예방적 차원의 농작물 가격 안정성 메커니즘을 제공합니다.</li>
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
  </ul>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/carousel_01.jpg" alt="블록체인 기술을 사용하는 이유는 무엇입니까?" width="1100" height="500">
      <div class="carousel-caption">
        <h5><span class="question-icon">Q: </span>블록체인 기술을 사용하는 이유는 무엇입니까?</h5>
        <p>
		<ol>
			<li>농업 산업의 데이터 수집 방법 및 데이터 활용 방법의 획기적인 개선</li> 
			<li>데이터 소유권 및 인센티브 설계 메커니즘</li>
			<li>농(축)산물의 유통 이력의 투명한 공개 및 활용</li>
		</ol>
		</p>
      </div>   
    </div>
    <div class="carousel-item">
      <img src="img/carousel_02.jpg" alt="사라팜은 소셜 임팩트 혹은 사회 공헌적인 프로젝트입니까?" width="1100" height="500">
      <div class="carousel-caption">
        <h5><span class="question-icon">Q: </span>사라팜은 소셜 임팩트 혹은 사회 공헌적인 프로젝트입니까?</h5>
        <p> 
		네, 그렇습니다. 사라팜은 다음과 같은 네 가지 문제를 해결하고자 합니다. 
 		<ol>
		  <li>농작물 가격 변동성과 영세농의 불안정성한 소득 문제를 해결하고자 합니다.</li>
		  <li>서울-농촌간 소득의 양극화 문제와 디지털 양극화 문제를 해결하고자 합니다.</li>
		  <li>농민들의 데이터 주권의식과 농업 데이터 활용 문제를 해결합니다.</li>
		  <li>식량 안보와 식량 자급률 문제를 해결하고자 합니다.</li>
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
  <h2>4. 사라팜 팀</h2>
  <p>사라팜은 블록체인을 통해 농촌경제 및 지속가능성 문제를 해결하고자하는 ‘사회개혁(social impact)’ 프로젝트로서 사내벤처팀으로 출발하였으며 블록체인 콘텐츠 커뮤니티 리더와 농수산유통 전문가로 구성된 팀입니다. </p>
<div class="card-deck">
    <div class="card">
      <img src="img/team_01.jpg" alt="BB Kang" style="width:100%">
      <div class="container">
        <h3>강보영(BB Kang)</h3>
        <p class="title">사라팜 대표창업자</p>
        <p>사라팜 토큰이코노미 아키텍트
사라팜 중장기전략기획 담당자
에피토미씨엘 CKO
동서증권 애널리스트
카이스트 미래전략대학원 졸업
미국 조지워싱턴 MBA 
연세대 경영학과 졸업
연세대 식품공학과 졸업
		</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_02.jpg" alt="Marian Kulisch" style="width:100%">
      <div class="container">
        <h3>Marian Kulisch</h3>
        <p class="title">사라팜 공동창업자</p>
        <p>사라팜 블록체인 개발자
에피토미씨엘 블록체인개발자
Knowre 안드로이드 자바개발자
베를린 보이트기술대학교 미디어정보학
</p>
      </div>
    </div>
    <div class="card">
      <img src="img/team_03.jpg" alt="Growth hacker" style="width:100%">
      <div class="container">
        <h3>사회혁신가</h3>
        <p class="title">Growth hacker</p>
        <p>자격요건: 데이터사이언스, 소프트웨어 공학, 전자성거래 경력 1년 이상
뛰어난 문서 작성 및 커뮤니케이션 능
</p>
      </div>
    </div>	
</div>
</div>
</section>

<section class="container_section bg-light" id="contact">
<div class="container">
<h2>데이터</h2>
  <p>
  농부 여러분들이 가꾸는 농산물과 재배 일정은 사라팜의 4P 농업혁신의 근간이 됩니다. 정보를 입력해주시면 저희 사라팜의 컨설턴트가 빠른 시간내에 응답해 드립니다. 
  <b class="status" id="status"></b>
  </p>
<form id="contact-form" action="/">
<div class="row">
  <div class="col-sm-6">
	<div class="form-group"><input type="text" required="" placeholder="이름" class="form-control" id="name" name="name"></div>
  </div>
  <div class="col-sm-6">
	<div class="form-group"><input type="text" required="" placeholder="이메일" class="form-control" id="email" name="email"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
	<div class="form-group"><textarea class="form-control" rows="5" required="" placeholder="메시지"  id="message" name="message"></textarea></div>
  </div>
</div>  
<div class="row">
  <div class="col-sm-6">
	<div class="form-group"><input type="tel" placeholder="전화" class="form-control" id="phone" name="phone"></div>
  </div>
  <div class="col-sm-6">
	<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">날짜</span>
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
    <input type="text" class="form-control" placeholder="대답" id="answer" name="answer">
	<input type="hidden" value="<?php echo $question; ?>" id="question" name="question">
  </div>
  </div>
</div>
<button type="button" class="btn btn-primary btn-block" onclick="validateForm()">제출해주세요</button>
  </form>
</div>
</section>

<section class="container_section bg-light" id="partner">
<div class="container">
  <h2>사라팜과 함께 하는 사람들</h2>
<div class="card-deck">
    <div class="card bg-light text-dark btn-outline-success">
		<div class="card-body">
			<h4 class="card-title">EPINET</h4>
			<p class="card-text">에피넷</p>
			<a href="http://www.epinet.co.kr/" class="btn btn-primary">epinet.co.kr</a>
		</div>
    </div>
    <div class="card bg-light text-dark btn-outline-success">
		<div class="card-body">
			<h4 class="card-title">SUGEUP</h4>
			<p class="card-text">aT농산물유통종합정보시스템</p>
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
			<p class="card-text">경기도 농업기술원</p>
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
		document.getElementById('status').innerHTML = "이름 필드를 작성하십시오.";
		return false;
	}
	var email =  document.getElementById('email').value;
	if (email == "") {
		document.getElementById('status').innerHTML = "이메일 필드를 작성하십시오.";
		return false;
	} else {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(!re.test(email)){
			document.getElementById('status').innerHTML = "이메일 형식이 잘못되었습니다.";
			return false;
		}
	}
	var message =  document.getElementById('message').value;
	if (message == "") {
		document.getElementById('status').innerHTML = "메시지 필드를 작성하십시오.";
		return false;
	}
	var phone =  document.getElementById('phone').value;
	if (phone == "") {
		document.getElementById('status').innerHTML = "전화 필드를 작성하십시오.";
		return false;
	}	
    var answer =  document.getElementById('answer').value;
	if (answer == "") {
		document.getElementById('status').innerHTML = "답변 필드를 작성하십시오.";
		return false;
	}
  
    document.getElementById('status').innerHTML = "제출 중...";

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
			return "귀하의 메시지가 전송되었으며 양식이 재설정되었습니다.";
		default:
			return "필드를 올바르게 입력하십시오.";
	}
}
    </script>   
</body>
</html>

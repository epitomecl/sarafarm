<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("classes/Autoloader.php");

use \Exception as Exception;

use modules\Profile as Profile;
use modules\Contact as Contact;
use modules\FoodImage as FoodImage;
use modules\Vegetarian as Vegetarian;
use modules\FoodMenu as FoodMenu;
use modules\SummaryChart as SummaryChart;
use modules\FoodInfo as FoodInfo;

// Allow CORS
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');    
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
}   
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Headers: *");
}

header("Content-Type: text/html; charset=utf-8");

session_start();

function getParam($array, $param, $label = '') {
	if (array_key_exists($param, $array)) {
		if (strcmp($label, "array") == 0) {
			return $array[$param];
		} elseif (strcmp($label, "int") == 0) {
			return intval(trim($array[$param]));
		} elseif (strcmp($label, "double") == 0) {
			return doubleval(trim($array[$param]));
		} else {
			return strip_tags(stripslashes(trim($array[$param])));
		}
	}

	return null;
}

$module = getParam($_POST, "module");
$httpMethod = $_SERVER["REQUEST_METHOD"];

if ($httpMethod == 'POST' && array_key_exists('NGINX', $_POST)) {
    if ($_POST['NGINX'] == 'DELETE') {
        $httpMethod = 'DELETE';
    } else if ($_POST['NGINX'] == 'PUT') {
        $httpMethod = 'PUT';
    }
}

if (empty($module)) {
	$module = getParam($_GET, "module");
}

$config = parse_ini_file("include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);

try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");

	switch(strtoupper($module)) {
		case "PROFILE":
			$profile = new Profile($mysqli);
			if ($httpMethod == "POST") {
				$profileId = intval(getParam($_POST, "profileId")); 
				$firstName = getParam($_POST, "firstName");
				$lastName = getParam($_POST, "lastName");
				$alias = getParam($_POST, "alias");
				$email = getParam($_POST, "email");
				$about = getParam($_POST, "about");				
				$file = isset($_FILES["file"]) ? $_FILES["file"] : array();
				$imageData = getParam($_POST, "imageData");
				$profile->doPost($profileId, $firstName, $lastName, $alias, $email, $about, $file, $imageData);
			} else {
				$userId = intval(getParam($_GET, "userId")); 
				$profile->doGet($userId);				
			}
			break;
		case "CONTACT":
			$contact = new Contact($mysqli);
			if ($httpMethod == "POST") {
				$name = getParam($_POST, "name");
				$email = getParam($_POST, "email");
				$message = getParam($_POST, "message");
				$phone = getParam($_POST, "phone");
				$date = getParam($_POST, "date");		
				$question = getParam($_POST, "question");
				$answer = getParam($_POST, "answer");				
				$contact->doPost($name, $email, $message, $phone, $date, $question, $answer);
			}
			break;
		case "FOODIMAGE":
			$foodImage = new FoodImage($mysqli);
			if ($httpMethod == "POST") {
				$foodCode = getParam($_POST, "foodCode", "int");
				$file = isset($_FILES["file"]) ? $_FILES["file"] : array();
				$imagedata = getParam($_POST, "imageData");
				$foodImage->doPost($foodCode, $file, $imagedata);
			}
			break;
		case "VEGETARIAN":
			$vegetarian = new Vegetarian($mysqli);
			if ($httpMethod == "POST") {
				$column = getParam($_POST, "column");
				$checked = getParam($_POST, "checked", "int");
				$foodCode = getParam($_POST, "foodCode", "int");
				$vegetarian->doPost($foodCode, $column, $checked);
			}
			break;
		case "FOODMENU":
			$instance = new FoodMenu($mysqli);
			if ($httpMethod == "GET") {
				$page = getParam($_GET, "page", "int");
				$itemsPerPage = getParam($_GET, "itemsPerPage", "int");
				$dialog = getParam($_GET, "dialog");				
				$instance->doGet($page, $itemsPerPage, $dialog);
			}
			break;	
		case "FOODINFO":
			$instance = new FoodInfo($mysqli);
			if ($httpMethod == "GET") {
				$foodCode = getParam($_GET, "foodCode", "int");				
				$instance->doGet($foodCode);
			}
			break;			
		case "SUMMARYCHART":
			$instance = new SummaryChart($mysqli);
			if ($httpMethod == "GET") {
				$arrBreakfast = getParam($_GET, "breakfast", "array");
				$arrLunch = getParam($_GET, "lunch", "array");
				$arrDinner = getParam($_GET, "dinner", "array");
				$arrSnack = getParam($_GET, "snack", "array");
				$instance->doGet($arrBreakfast, $arrLunch, $arrDinner, $arrSnack);
			}
			break;			
		default:
			break;		
	}
} catch (Exception $e) {
	$msg = $e->getMessage();
	$code = $e->getCode();
	http_response_code(($code == 0) ? 400 : $code);
	echo sprintf("Exception occurred in: %s", $msg);
} finally {
	$mysqli->close();
}

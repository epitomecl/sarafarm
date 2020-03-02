<?php

$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);
$reports = array();
	
try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT report.id, firstname, lastname, crop, report.area, calculatedAmountLastYear, calculatedAmountThisYear, unit, acreage ";
	$sql .= "FROM report ";
	$sql .= "LEFT JOIN user ON (user.id = report.user_id) ";
	$sql .= "LEFT JOIN profile ON (profile.id = user.profile_id); ";
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$item = new stdClass;
			$item->id = $row["id"];
			$item->firstname = trim($row["firstname"]);
			$item->lastname = trim($row["lastname"]);
			$item->crop = intval($row["crop"]);
			$item->area = trim($row["area"]);			
			$item->unit = intval($row["unit"]);
			$item->acreage = intval($row["acreage"]);			
			$item->calculatedAmountLastYear = intval($row["calculatedAmountLastYear"]);
			$item->calculatedAmountThisYear = intval($row["calculatedAmountThisYear"]);			
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

$csv = array("ID;PERSON;REGION;CROP;LASTYEAR;THISYEAR;FIELDSIZE");
	
foreach ($reports as $index => $report) {
	$crop = "";
	switch ($report->crop) {
		case 1 : $crop = "Radish"; break;
		case 2 : $crop = "Pepper"; break;
		case 3 : $crop = "Onion"; break;
		case 2 : $crop = "Garlic"; break;
		case 3 : $crop = "Cabbage"; break;			
	}

	$factor = array("sqm" => (1 / 100), "pyong" => (3.300579 / 100), "are" => 1, "ha" => (10000 / 100));
	$unit = "";
	switch ($report->unit) {
		case 1 : $unit = "sqm"; break;
		case 2 : $unit = "pyong"; break;
		case 3 : $unit = "are"; break;
		case 2 : $unit = "ha"; break;
	}		

	$line = array();
	array_push($line, $report->id);
	array_push($line, $report->lastname." ".$report->firstname);
	array_push($line, $report->area);
	array_push($line, $crop);
	array_push($line, $report->calculatedAmountLastYear);
	array_push($line, $report->calculatedAmountThisYear);
	array_push($line, $report->acreage * $factor[$unit]);
	array_push($csv, implode(";", $line));
}

$filename = "reports.csv";
header('Content-Disposition: attachment;filename="'.$filename.'";');
header('Content-Type: application/csv; charset=UTF-8');
echo implode(PHP_EOL, $csv);


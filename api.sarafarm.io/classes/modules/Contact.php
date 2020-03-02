<?php

namespace modules;

use \Exception as Exception;

/**
* 
*/
class Contact {
	private $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	/**
	* something describes this method
	*
	* @param string $name The name of visitor
	* @param string $email The email of visitor
	* @param string $message The message of visitor
	* @param string $phone The phone of visitor
	* @param string $date The date of callback
	* @param string $question The question key
	* @param string $answer The answer	
	*/		
	public function doPost($name, $email, $message, $phone, $date, $question, $answer) {
		$mysqli = $this->mysqli;

		$this->validate($name, $email, $message, $phone, $question, $answer);
		
		$name = $mysqli->real_escape_string($name);
		$email = $mysqli->real_escape_string($email);
		$message = $mysqli->real_escape_string($message);
		$phone = $mysqli->real_escape_string($phone);
		$date = $mysqli->real_escape_string($date);
		
		$sql = "INSERT contact ";
		$sql .= "SET name='%s', email='%s', message='%s', ";
		$sql .= "phone='%s', date='%s', modified=NOW() ";
		$sql = sprintf($sql, $name, $email, $message, $phone, $date);
		
		if ($mysqli->query($sql) === false) {
			throw new Exception(sprintf("%s, %s", get_class($this), $mysqli->error), 507);
		}
		
		echo json_encode(array("status" => 200), JSON_UNESCAPED_UNICODE);		
	}
	
	private function getAnswer($question) {
		$answer = "";		
		$text = array(
			"12 * 12 =" => "144",
			"11 * 11 =" => "121",
			"10 * 11 =" => "110",
			"144 - 12 =" => "132",
			"512 / 4 =" => "128",
			"1024 - 512 =" => "512",
			"27 * 6 =" =>  "162");
	
		if (array_key_exists($question, $text)) {
			$answer = $text[$question];
		}
		
		return $answer;
	}
	
	private function validate($name, $email, $message, $phone, $question, $answer) {
		if ($name === ''){
			throw new Exception('Your name cannot be empty.', 507);
		}
		if ($email === ''){
			throw new Exception('Email cannot be empty.', 507);
		} else {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				throw new Exception('Email format invalid.', 507);
			}
		}
		if ($phone === ''){
			throw new Exception('Phone cannot be empty.', 507);
		}
		if ($message === ''){
			throw new Exception('Message cannot be empty.', 507);
		}
		if (strcmp($answer, $this->getAnswer($question)) != 0) {
			throw new Exception('Anti-spam answer is wrong.', 507);
		}		
	}
}
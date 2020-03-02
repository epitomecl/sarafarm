<?php

namespace modules;

use \Exception as Exception;

/**
* If user session is alive, food data will be managed.
* A POST request will update food data.
*/
class FoodImage {
	private $mysqli;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function doPost($foodCode, $file, $base64imagedata) {
		$tokens = explode(".", trim($file["name"]));
		$extension = end($tokens);
		$extension = strtolower($extension);
		$path = realpath(dirname(__FILE__).'/../../../');
		
		if (strlen($base64imagedata) > 0 && strlen($extension) > 0 && $foodCode > 0) {

			$filepath = "/img/food/";
			$filename = $foodCode;
			$image = "";
			
			$tokens = explode(",", $base64imagedata, 2);
			$imageData = base64_decode(end($tokens));
			$im = imagecreatefromstring($imageData);
			if ($im !== false) {
				switch ($extension) {
					case "jpeg":						
					case "jpg":
						imagejpeg($im, $path.$filepath.$filename.".jpg", 75);
						imagedestroy($im);
						$image = $filepath.$filename.".jpg";						
						break;
					case "png":
						imagepng($im, $path.$filepath.$filename.".png");
						imagedestroy($im);
						$image = $filepath.$filename.".png";						
						break;	
					case "gif":
						imagegif($im, $path.$filepath.$filename.".gif");
						imagedestroy($im);
						$image = $filepath.$filename.".gif";						
						break;
				}
				
	
				if (!empty($image)) {
					$sql = "UPDATE food SET image='%s' WHERE food_code=%d";
					$sql = sprintf($sql, $image, $foodCode);

					if ($this->mysqli->query($sql) === false) {
						throw new Exception(sprintf("%s, %s", get_class($this), $this->mysqli->error), 507);
					}
				}
			}
		}
		
		echo json_encode(array("foodCode" => $foodCode), JSON_UNESCAPED_UNICODE);
	}
}
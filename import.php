<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$csv = array();
$msgs = array();

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['file']['error'] value.
    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['file']['size'] > 5000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

	$database = $_FILES['file']['name'];
    $csvData = file_get_contents($_FILES['file']['tmp_name']);
	$lines = explode(PHP_EOL, trim($csvData));
	$keys = str_getcsv(array_shift($lines), $delimiter = ",", $enclosure = '"', $escape = "\\");

	foreach ($lines as $index => $line) {
		$data = str_getcsv($line, $delimiter = ",", $enclosure = '"', $escape = "\\");
		$item = array_combine($keys, $data);
		array_push($csv, $item);
	}
	
	$data = array();
	foreach ($csv as $index => $item) {
		$columns = array_keys($item);
		$row = array();
		foreach ($columns as $a => $column) {
			$row[$column] = $item[$column];
			// php mapping error
			if ($a == 0) {
				unset($row[$column]);
				$row["food_code"] = $item[$column];
			}
		}

		$foodCode = intval($row["food_code"]);
		
		switch ($row["meal_category"]) {
			case "I":
				$row["meal_category"] = 1;
				break;
			case "II":
				$row["meal_category"] = 2;
				break;
			case "III":
				$row["meal_category"] = 3;
				break;
		}
		$row["image"] = sprintf("/img/food/%s.jpg", $foodCode);
		
		$token = explode(",", $item["vegan_category"]);
		$row["vegan"] = (in_array("I", $token)) ? 1 : 0;
		$row["lacto_vegan"] = (in_array("II", $token)) ? 1 : 0;
		$row["ovo_vegan"] = (in_array("III", $token)) ? 1 : 0;
		$row["pescatarian"] = (in_array("IV", $token)) ? 1 : 0;		
		
		unset($row["vegan_category"]);
		
		array_push($data, $row);
	}
		
    array_push($msgs, 'File is uploaded successfully.');

} catch (RuntimeException $e) {
    array_push($msgs, $e->getMessage());
}

$config = parse_ini_file("api.sarafarm.io/include/db.mysql.ini");
$mysqli = new mysqli($config['HOST'], $config['USER'], $config['PASS'], $config['NAME']);

try {
	if ($mysqli->connect_error) {
		throw new Exception("Cannot connect to the database: ".$mysqli->connect_errno, 503);
	}
	$mysqli->set_charset("utf8");
	
	$tags = array();
	$sql = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH ";
	$sql .= "FROM information_schema.COLUMNS ";
	$sql .= "WHERE TABLE_SCHEMA = DATABASE() ";
	$sql .= "AND TABLE_NAME = '%s' ";
	$sql .= "ORDER BY ORDINAL_POSITION";
	$sql = sprintf($sql, "food");

	if ($result = $mysqli->query($sql)) {
		while ($field = $result->fetch_assoc()) {
			array_push($tags, "`" . trim($field["COLUMN_NAME"]) . "`");
		}	
	}
	array_shift($tags);

	foreach ($data as $index => $item) {
		$columns = array_keys($item);
		$values = array();
		$columnNames = array();
		foreach ($columns as $a => $column) {
			array_push($values, $mysqli->real_escape_string($item[$column]));
			array_push($columnNames, "`".$column."`");
		}
		
		$sql = "INSERT INTO food (%s) VALUES ('%s');";
		$sql = sprintf($sql, implode(",", $columnNames), implode("','", $values));
		if (!$mysqli->query($sql)) {
			array_push($msgs, $sql);
			array_push($msgs, sprintf("Error: %s, table food\n", $mysqli->error));
			break;
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
  <title>SaraFarm import</title>
  <link rel="shortcut icon" href="https://sarafarm.io/img/sarafarm.io.ico" />  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
	  
  </style>
</head>
<body>

<div class="jumbotron bg-cover">
  <div class="overlay"></div>
  <div class="container">
  <h1>SaraFarm Veggie</h1>
  <p>The paradigm shift for vegetarian food!</p>
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
        <a class="nav-link" href="/veggie.php">Veggie Plan</a>
      </li>
    </ul>
  </div>  
</nav>
	<div style="margin-bottom:10px;padding:10px; border: 1px solid black;">
		<?php echo implode("<hr>", $msgs); ?>
	</div>

	<div style="margin-bottom:10px;padding:10px; border: 1px solid black;">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
			<label for="file">Filename: </label><input type="file" name="file" id="file" />
			<input type="submit" name="submit" value="Submit" />
		</form>
	</div>
	
	<?php if (count($data) > 0) { ?>

	<caption>Counter: <?php echo count($data); ?></caption>
	<table border="1" cellpadding="2" cellspacing="2">
<?php
foreach ($data as $index => $item) {
	$columns = array_keys($item);
	
	if ($index == 0) {
		echo sprintf("<tr><th>%s</th></tr>\n", implode("</th><th>", $columns));
	}
	if ($index == 0) {
		echo sprintf("<tr><th>%s</th></tr>\n", implode("</th><th>", $tags));
	}	
	$values = array();
	foreach ($columns as $column) {
		array_push($values, $item[$column]);
	}

	echo sprintf("<tr style=\"background-color:#E7FFDF;\"><td>%s</td></tr>\n", implode("</td><td>", $values));
	
	if ($index > 100) {
		break;
	}
}	
?>	
	</table>
	<?php } ?>



<form method="post" action="import.php" enctype="multipart/form-data" name="insertForm">
<input type="hidden" name="imageData" id="imageData" value="">
<input type="hidden" name="fields[foto]" value="{ma_foto}">
	
<table border="0" cellspacing="0" cellpadding="2" width="100%" style="border:1px solid #9D9D9D;">

<tr bgcolor="#FDF3EA">
	<td align="left" height="5" colspan="6">
		<table cellspacing="2" cellpadding="2" border="0" width="100%">
		<tr>
			<td>
				<a href="indexPure.php?mod=maViewFoto&maID={ma_id}" target="_blank"><img class="profileImagePreview" src="{ma_thumblink}" width="118" height="151.7" border="0"></a>
			</td>
			<td valign="bottom" width="100%">Click on image to enlarge</td>
			<td valign="bottom" nowrap>512 x 512 Pixel (BxH)</td>
			<td valign="bottom" nowrap style="display:{ma_display_picture_update}"><span style="width:8px; height:14px; vertical-align:middle;">
			<img src="img/pfeilVor.gif" width="8" height="9">
		</span><span class="profileImageButton">Food image exchange</span></td>
			<td valign="bottom" nowrap style="display:{ma_display_picture_delete}"><span style="width:8px; height:14px; vertical-align:middle;">
			<img src="img/pfeilVor.gif" width="8" height="9">
		</td>
		</tr>
		</table>
	</td>
</tr>

</table>
</form>



<style>
      .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 5px solid #ccc;
        border-radius: 3px;
        margin-top: 20px;
        width: 512px;
        height: 521px;
      }

	  .cropit-image-input {
		color: white;
		border: none;
		display:inline;
	  }
	  
	  .profileImageClose {
		display: inline;
		float:right;
		font-size: 12pt;
	  }
	  
	  .profileImageDelete, .profileImageButton {
		cursor:pointer;
	  }
	  
      .cropit-preview-image-container {
        cursor: move;
      }

      .cropit-preview-background {
        opacity: .2;
        cursor: auto;
      }

      .image-size-label {
		margin-left: 10px;
        margin-top: 10px;
      }

      input.image-editor {
        /* Use relative position to prevent from being covered by image background */
        position: relative;
        z-index: 10;
        display: block;
      }

      button.image-editor {
        margin-top: 10px;
      }
	  
	.overlay {
		position: fixed;
		display: none;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0,0,0,0.5);
		z-index: 8;
		cursor: pointer;
	}  
	.popup {
		position: absolute;
		top: 50%;
		left: 50%;
		color: white;
		transform: translate(-50%,-50%);
		-ms-transform: translate(-50%,-50%);
	}
	.popup .settings {
		display:table-cell;
	}
	.popup button, .popup input[type=range] {
		display: table-cell;
		vertical-align: middle;
	}

	.profileImagePreview {
		background-color: white;
	}
</style>

	<div class="overlay">
	<div class="popup">
    <div class="image-editor">
		<div class="headline">
			<input type="file" name="foto" class="cropit-image-input" accept="image/jpeg, image/gif, image/png">
			<div class="profileImageClose">[ X ]</div>
		</div>
      <div class="cropit-preview"></div>
      <div class="image-size-label">
        image size slider
      </div>
	  <div class="settings">
		<input type="range" class="cropit-image-zoom-input">
		<button class="rotate-ccw">left rotate</button>
		<button class="rotate-cw">right rotate</button>
		<button class="export">ready</button>
	  </div>
    </div>
	</div>
	</div>
	
<footer class="page-footer font-small pt-4">
  <div class="footer-copyright text-center py-3">Copyright &copy;
    <a href="https://sarafarm.io">SaraFarm.io</a>
  </div>
</footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="/js/jquery.cropit.js"></script>
  
    <script>
	$(function() {
        $('.image-editor').cropit({
          exportZoom: 1.0,
		  maxZoom: 4.0,
		  smallImage: 'allow',
          imageBackground: true,
		  allowDragNDrop: false,
          imageBackgroundBorderWidth: 20,
          imageState: {
            src: 'indexPure.php?mod=maViewFoto&maID={ma_id}',
          },
        });

        $('.rotate-cw').on("click", function(event) {
			event.preventDefault();
			event.stopPropagation();
          $('.image-editor').cropit('rotateCW');
        });
        $('.rotate-ccw').on("click", function(event) {
			event.preventDefault();
			event.stopPropagation();
          $('.image-editor').cropit('rotateCCW');
        });

        $('.export').on("click", function(event) {
			event.preventDefault();
			event.stopPropagation();
		
          var imageData = $('.image-editor').cropit('export');
          $("img.profileImagePreview").prop("src", imageData);
		  $("#imageData").val(imageData);
		  $("div.overlay").hide();
        });
      });
	  
	  $(".profileImageClose").on("click", function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		$("div.overlay").hide();
	  });
	  
	  $(".profileImageButton").on("click", function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		$("div.overlay").show();
	  });
	  
    </script>



</body>
</html>

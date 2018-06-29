﻿<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<meta name="description" content="description"/>
	<meta name="keywords" content="keywords"/> 
	<meta name="author" content="author"/> 
	<link rel="stylesheet" type="text/css" href="default.css" media="screen"/>
	<title>"I can"</title>

    <style>
        .top {
			background: transparent;
			padding: 10px;
			font-family: "Bebas Neue", arial;
			font-size: 36px;
			width: 700px;
			border: 5px solid black;
		}
		
		
		.custom-select select {
            background: #1793B8;
            width: 268px;
            padding: 5px;
			font-family: "Bebas Neue", arial;
            font-size: 16px;
            line-height: 1;
            border: 0;
            border-radius: 0;
            height: 34px;
            -webkit-appearance: none;
        }

		.content {
			background: transparent;
			font-family: "Bebas Neue", arial;
			font-size: 16px;
		}

    </style>

</head>

<body>

<div class="container">

	<div class="top">
		<a href="index.html"><span>I Can</span></a>
	</div>
	
	<div class="header"></div>
		
	<div class="main">
		<form enctype="multipart/form-data" action="upload.php" method="POST">

		<div class="item">

			<div class="content">

				<h1>Select  button</h1>

				<div class="custom-select" style="width:200px;">

					<select name="button">
						<option value="Cliff">Cliff</option>
						<option value="Goldfish">Goldfish</option>
						<option value="Pistachiosn">Pistachiosn</option>
						<option value="Hefty">Hefty</option>
					</select>

				</div>

			</div>

		</div>
		
		<div class="item">

			<div class="content">

				<h1>Choose button type</h1>

				<div class="custom-select" style="width:200px;">

					<select name ="buttonType">
						<option value="sound">Sound</option>
						<option value="light">Light</option>
					</select>
    
				</div>

			</div>

		</div>

		<div class="item">

			<div class="content">

				<h1>Upload</h1>

				<div class="custom-select" style="width:200px;">
		
					<p>Upload file</p>
					<input type="file" name="fileName"></input> <br/>
					<input type="submit" value="submit"></input>
    
				</div>

			</div>

		</div>

		</form>
	</div>
	
	<div class="clearer"><span></span></div>

</div>

</body>

</html>


<?PHP
if(isset($_POST['button'])){
	$jsonfile = "amazon-dash.json";
    $selectedBtn = $_POST['button'];
    $selectedType = $_POST['buttonType'];
    $string = file_get_contents($jsonfile);
    $json_a = json_decode($string, true);
    
    if(!empty($_FILES['fileName']))
      {
        $path = "uploads/";
        $ext = pathinfo($_FILES['fileName']['name'], PATHINFO_EXTENSION);
        $newName = $selectedBtn.".".$ext;
        $path = $path.$newName;
        if(move_uploaded_file($_FILES['fileName']['tmp_name'], $path)) {
          echo "The file ".  $newName. " has been uploaded";
        } else{
            echo "There was an error uploading the file, please try again!";
        }
      }
	$newJson = array();
    foreach($json_a as $key => $value)
    {
	  $newJson[$key] = $value;
      if ($key == 'devices')
      {
            foreach($value as $mac => $detail) {
                if(trim($detail['name']) == trim($selectedBtn)) {
					$path = $_SERVER['DOCUMENT_ROOT']."/shehacks2018team12/".$path;
                       $cmd = ($selectedType == 'sound')? "omxplayer ".$path." --vol 1000" : 'curl -X POST https://maker.ifttt.com/trigger/dash_button_press/with/key/felqP2zXnKwE5LsqkLXZf7koMVNYDP9P2L_k4iXWQkJ';
						$newJson[$key][$mac]['cmd'] = $cmd;
                }
            }
            
      }
    }
	$fp = fopen($jsonfile, 'w');
	fwrite($fp, json_encode($newJson));
	fclose($fp);
	
	$output = shell_exec('./update_config.sh');
    
   
}
?>
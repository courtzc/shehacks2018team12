<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<meta name="description" content="description"/>
	<meta name="keywords" content="keywords"/> 
	<meta name="author" content="author"/> 
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<title>"I can"</title>
	<style>
	html, body { height:100%; }

.outer-wrapper { 
display: table;
width: 100%;
height: 100%;
}

.inner-wrapper {
  display:table-cell;
  vertical-align:middle;
  padding:15px;
}
.login-btn { position:fixed; top:15px; right:15px; }
	</style>

</head>

<body>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme 
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">-->
<a class="btn btn-default login-btn" href="#loginform">I Can </a>
<section id="loginform" class="outer-wrapper">

 <div class="inner-wrapper">
<div class="container">
  <div class="row">
    <div class="col-sm-4 col-sm-offset-4">
      <h2 class="text-center">I Can</h2>
      <form role="form" enctype="multipart/form-data" action="upload.php" method="POST">
	  <div class="form-group">
		<label for="exampleInputEmail1">Select Button</label>
		<select name="button" class="form-control">
			<option value="Cliff">Cliff</option>
			<option value="Goldfish">Goldfish</option>
			<option value="Pistachiosn">Pistachiosn</option>
			<option value="Hefty">Hefty</option>
		</select>
	  </div>
	  <div class="form-group">
		<label for="exampleInputEmail1">Select Button Type</label>
		<select name ="buttonType" class="form-control">
			<option value="sound">Sound</option>
			<option value="light">Light</option>
		</select>
	  </div>
	  <div class="form-group">
		<label for="exampleInputEmail1">Upload File</label>
		<input type="file" class="form-control" name="fileName"></input>
	  </div>
		<button type="submit" class="btn btn-default">Submit</button>
</form>
    </div>
  </div>
</div>
</div>

</section>

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
                       $cmd = ($selectedType == 'sound')? "omxplayer /".$path." " : 'curl -X POST https://maker.ifttt.com/trigger/dash_button_press/with/key/felqP2zXnKwE5LsqkLXZf7koMVNYDP9P2L_k4iXWQkJ';
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

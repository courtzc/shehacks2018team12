<!DOCTYPE html>
<html>

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/> 
<meta name="author" content="author"/> 
<link rel="stylesheet" type="text/css" href="default.css" media="screen"/>
<title>"I can"</title>
</head>

<body>

<div class="container">

	<div class="top">
		<a href="index.html"><span>Bitter Sweet</span></a>
	</div>
	
	<div class="header"></div>
		
	<div class="main">
		<form enctype="multipart/form-data" action="upload.php" method="POST">

		<div class="item">


			<div class="content">

				<h1>Select  button</h1>

				<div class="body">

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

			<div class="date">
				<div>SEP</div>
				<span>30</span>
			</div>

			<div class="content">

				<h1>Choose button type</h1>

				<div class="body">

					<select name ="buttonType">
						<option value="sound">Sound</option>
						<option value="light">Light</option>
					</select>
    
				</div>

			</div>

		</div>

		<div class="item">

			<div class="date">
				<div>SEP</div>
				<span>30</span>
			</div>

			<div class="content">

				<h1>Upload</h1>

				<div class="body">
		
					<p>Upload file</p>
					<input type="file" name="fileName"></input> <br/>
					<input type="submit" value="submit"></input>
    
				</div>

			</div>

		</div>

		</form>
	</div>

	<div class="navigation">

		<h1>Something</h1>
		<ul>
			<li><a href="index.html">pellentesque</a></li>
			<li><a href="index.html">sociis natoque</a></li>
			<li><a href="index.html">semper</a></li>
			<li><a href="index.html">convallis</a></li>
		</ul>

		<h1>Another thing</h1>
		<ul>
			<li><a href="index.html">consequat molestie</a></li>
			<li><a href="index.html">sem justo</a></li>
			<li><a href="index.html">semper</a></li>
			<li><a href="index.html">sociis natoque</a></li>
		</ul>

		<h1>Third and last</h1>
		<ul>
			<li><a href="index.html">sociis natoque</a></li>
			<li><a href="index.html">magna sed purus</a></li>
			<li><a href="index.html">tincidunt</a></li>
			<li><a href="index.html">consequat molestie</a></li>
		</ul>

		  

	</div>
	
	<div class="clearer"><span></span></div>

	<div class="footer">&copy; 2006 <a href="index.html">Website.com</a>. Valid <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> &amp; <a href="http://validator.w3.org/check?uri=referer">XHTML</a>. Template design by <a href="http://templates.arcsin.se">Arcsin</a>
	</div>

</div>

</body>

</html>


<?PHP

/*  echo $dir = "uploads/";
  $a = scandir($dir);
  $a = array_diff(scandir($dir), array('.', '..'));
  print_r($a);
*/

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
                       $cmd = ($selectedType == 'sound')? "omxplayer /".$path."  --vol 1000" : 'curl -X POST https://maker.ifttt.com/trigger/dash_button_press/with/key/felqP2zXnKwE5LsqkLXZf7koMVNYDP9P2L_k4iXWQkJ';
                        //$detail['cmd'] = $cmd;
						$newJson[$key][$mac]['cmd'] = $cmd;
                }
            }
            
      }
    }
	$fp = fopen($jsonfile, 'w');
	fwrite($fp, json_encode($newJson));
	fclose($fp);

    
   
}
?>




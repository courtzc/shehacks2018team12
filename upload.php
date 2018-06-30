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

		<div class="item">

			<div class="date">
				<div>OCT</div>
				<span>01</span>
			</div>

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

				<h1>Choose button type</h1>

				<div class="body">
		
					<p>Upload file</p>
					<input type="text" name="fileName"></input> <br/>
					<input type="submit" value="Upload"></input>
    
				</div>

			</div>

		</div>


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

		  <form enctype="multipart/form-data" action="upload.php" method="POST">

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


if($_POST['submit']) {

    $selectedBtn = $_POST['button'];
    $selectedType = $_POST['buttonType'];
    $string = file_get_contents("amazon-dash.json");
    $json_a = json_decode($string, true);

    print_r ($json_a);
    if(!empty($_FILES['uploaded_file']))
      {

        $path = "uploads/";
        $ext = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
        $newName = $selectedBtn.".".$ext;
        $path = $path.$newName;

        if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
          echo "The file ".  $newName. " has been uploaded";
        } else{
            echo "There was an error uploading the file, please try again!";
        }
      }

    foreach($json_a as $key => $value)
    {
      if ($key == 'devices')
      {
            foreach($value as $mac => $detail) {
                if($detail['name'] == $selectedBtn) {
                        $cmd = if($selectedType == 'sound')? "omxplayer ".$path."  --vol 1000" : 'curl -X POST https://maker.ifttt.com/trigger/dash_button_press/with/key/felqP2zXnKwE5LsqkLXZf7koMVNYDP9P2L_k4iXWQkJ';
                        $detail['cmd'] = $cmd;
                }
            }
            
      }
    }
     echo $updatedJson = json_encode($json_a);
    
   
}
?>




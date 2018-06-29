<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data"  method="POST">

    <p>Select  button</p>
    <select name="button">
        <option value="Cliff">Cliff</option>
        <option value="Goldfish">Goldfish</option>
        <option value="Pistachiosn">Pistachiosn</option>
        <option value="Hefty">Hefty</option>
    </select>

    <p> Choose button type</p>
    <select name ="buttonType">
        <option value="sound">Sound</option>
        <option value="light">Light</option>
    </select>
    
    <p>Upload file</p>
    <input type="file" name="fileName" ></input> <br/>
    <input type="submit" value="submit"></input>
  </form>


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




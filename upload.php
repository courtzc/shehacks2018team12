<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="upload.php" method="POST">

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
    <input type="text" name="fileName"></input> <br/>
    <input type="submit" value="Upload"></input>
  </form>


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




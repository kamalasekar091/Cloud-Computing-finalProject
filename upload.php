<?php
session_start();
    // Include the SDK using the Composer autoloader
     require 'vendor/autoload.php';
     $s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

include('image_validation.php'); // getExtension Method
$message='';
if($_SERVER['REQUEST_METHOD'] == "POST")
{
echo $name;
$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'];
$tmp = $_FILES['file']['tmp_name'];
$ext = getExtension($name);


$resultput = $s3->putObject(array(
             'Bucket'=>'raw-kro',
             'Key' =>  $name,
             'SourceFile' => $tmp,
             'region' => 'us-west-2',
              'ACL'    => 'public-read'
        ));
        $message = "S3 Upload Successful.";
        $imageurl=$resultput['ObjectURL'];
if($imageurl){
$_SESSION['imageurl']=$imageurl;
$_SESSION['keyname']=$name;
header( "Location: uploader.php" );
}
}

?>
<html>
<head>
<title>Uploaded Image</title>
<style>
body {
    margin: 0;
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 25%;
    background-color: #f1f1f1;
    position: fixed;
    height: 100%;
    overflow: auto;
}

li a {
    display: block;
    color: #000;
    padding: 8px 16px;
    text-decoration: none;
    border-bottom: 1px solid #555;
}

li a.active {
    background-color: #4CAF50;
    color: white;
}
li a:hover:not(.active) {
    background-color: #555;
    color: white;
}
.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
.button2 {
    background-color: #008CBA;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
</style>
</head>
<body>

<ul>
  <li><a href="/welcome.php">Home</a></li>
  <li><a href="/gallery.php">Gallery</a></li>

  <li><a class="active" href="/upload.php">Upload</a></li>
<?php
if($_SESSION['username']=="controller"){
echo "<li><a href=\"/admin.php\">Admin</a></li>";
}
?>
  
</ul>

<div style="margin-left:25%;padding:1px 16px;height:1000px;">
<h4 style="float:right" >welcome: <?php echo $_SESSION['username']; ?></h4>
<br>
<br>
<br>
<form action='' method='post' enctype="multipart/form-data">
<h1 style="color:blue;">Upload image file here</h1>
<input class"button2" type='file' name='file'/>
<br>
<br>
<br>
<input class="button" type='submit' value='Upload Image'/>
<br>
<?php echo $msg; ?>
</form>
</div>
</body>
</html>

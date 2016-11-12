<?php
session_start();
require 'vendor/autoload.php';

use Aws\Rds\RdsClient;

$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'itmo544-krose1-mysqldb',
));

$endpoint = "";
$url="";

foreach ($result['DBInstances'] as $ep)
{
   // echo $ep['DBInstanceIdentifier'] . "<br>";

    foreach($ep['Endpoint'] as $endpointurl)
        {
        echo "<h4>The url used to connect to the database</h4>";
        echo $endpointurl . "<br>";
echo "<br>";
        $url=$endpointurl;
                break;
        }
}

$conn = mysqli_connect($url,"controller","controllerpass","school","3306") or die("Error " . mysqli_error($link));
$statusnumber=0;

if (!($stmt2 = $conn->prepare("INSERT INTO records (id,email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES (NULL,?, ?, ?, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt = $conn->prepare("INSERT INTO records (email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $email, $phone, $s3_raw_url,$s3_finished_url,$status,$receipt);
$email="kamalasekar091@gmail.com";
$phone="6036744303";
$s3_raw_url=$_SESSION['imageurl'];
$s3_finished_url="summa";
$status=$statusnumber;
$receipt=md5($_SESSION['imageurl']);
$stmt->execute();
$stmt->close();
$conn->close();


if($_SERVER['REQUEST_METHOD'] == "POST")
{
	header( "Location: upload.php" );
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
</style>
</head>
<body>

<ul>
  <li><a href="/welcome.php">Home</a></li>
  <li><a href="/gallery.php">Gallery</a></li>
  <li><a href="/upload.php">Upload</a></li>
  <li><a href="/admin.php">Admin</a></li>
</ul>

<div style="margin-left:25%;padding:1px 16px;height:1000px;">
<h4 style="float:right" >welcome: <?php echo $_SESSION['username']; ?></h4>
<form action="" method='post' enctype="multipart/form-data">
<h1><?php echo $_SESSION['keyname']; ?><h1>
<img src="<?php echo $_SESSION['imageurl']; ?>" height="500" width="600">
<br>
<input type='submit' value='Return to upload PHP'/>
</form>
</div>
</body>
</html>

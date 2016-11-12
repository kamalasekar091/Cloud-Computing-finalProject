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
        $url=$endpointurl;
                break;
        }
}


$link = mysqli_connect($url,"controller","controllerpass","school","3306") or die("Error " . mysqli_error($link));

$sqlselect = "SELECT s3_raw_url FROM records";
$resultforselect = $link->query($sqlselect);



// if ($resultforselect->num_rows > 0) {
    // output data of each row
//   while($row = $resultforselect->fetch_assoc()) {

        //echo "<img src='$row[\"s3_raw_url\"]' height=\'500\' width=\'600\'/>";
    //}
// } else {
    //echo "0 results";
 //}


// $link->close();


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
  <li><a class="active" href="/gallery.php">Gallery</a></li>
  <li><a href="/upload.php">Upload</a></li>
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
<?php
if ($resultforselect->num_rows > 0) {
    // output data of each row
    while($row = $resultforselect->fetch_assoc()) {
		$value=$row["s3_raw_url"];

        echo "<img src='$value' height=\"200\" width=\"200\"/>";
    }
} else {
    echo "0 results";
}
$link->close();
?>
</div>
</body>
</html>

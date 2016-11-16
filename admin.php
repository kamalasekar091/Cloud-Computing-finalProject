<?php
session_start();

include 'checkuploadenabled.php';

$variable=returnenabledstatus();

?>
<html>
<head>
<meta charset=utf-8 />
<title>ADMIN</title>
<!-- <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script> -->
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
<?php
if($variable == 'on'){
  echo"<li><a href=\"/upload.php\">Upload</a></li>";
}
if($_SESSION['username']=="controller"){
echo "<li><a href=\"/admin.php\">Admin</a></li>";
}
?>

</ul>

<div style="margin-left:25%;padding:1px 16px;height:1000px;">
<h4 style="float:right" >welcome: <?php echo $_SESSION['username']; ?></h4>
<br>
<h1>Currently the value is <?php if($variable == 'on'){echo 'ENABLED'; }  else {echo 'DISABLED'; } ?> for all user</h1>
<br>
<br>
<form id='login' action='changestatus.php' method='post' accept-charset='UTF-8'>
<select data-role="slider" name="flagstatus" id="flag">
<option value="off" accesskey="">OFF</option>
<option value="on">ON</option>
</select>
<br>
<br>
<input type='submit' name='Submit' value='Submit' />
</form>
<script>
 $('#flag option[value=<?php echo $variable; ?>]').prop('selected', true);
</script>
</div>
</body>
</html>

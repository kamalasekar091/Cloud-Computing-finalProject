<?php

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
    echo $ep['DBInstanceIdentifier'] . "\n";

    foreach($ep['Endpoint'] as $endpointurl)
	{
        echo $endpointurl . "\n";
        $url=$endpointurl . "\n";
		break;
	}
}


$link = mysqli_connect("itmo544-krose1-mysqldb.czynbl6qv9oh.us-west-2.rds.amazonaws.com","controller","controllerpass","school","3306") or die("Error " . mysqli_error($link));

// echo "Here is the result: " . $link;

$drop_table = 'DROP TABLE IF EXISTS students';
$drop_tbl = $link->query($drop_table);
if ($drop_table) {
        echo "Table student has been deleted";
}
else {
        echo "error!!";

}


$create_table = 'CREATE TABLE IF NOT EXISTS students
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    age int(3) NOT NULL,
    PRIMARY KEY(id)
)';

$create_tbl = $link->query($create_table);
if ($create_table) {
        echo "Table is created or No error returned.";
}
else {
        echo "error!!";
}

$sql1 = "INSERT INTO students (name,age) values ('Student-1',23)";

$sql2 = "INSERT INTO students (name,age) values ('Student-2',24)";

$sql3 = "INSERT INTO students (name,age) values ('Student-3',36)";

$sql4 = "INSERT INTO students (name,age) values ('Student-4',23)";

$sql5 = "INSERT INTO students (name,age) values ('Student-5',31)";


$sql_execute1 = $link->query($sql1);
$sql_execute2 = $link->query($sql2);
$sql_execute3 = $link->query($sql3);
$sql_execute4 = $link->query($sql4);
$sql_execute5 = $link->query($sql5);

if (sql_execute1 && sql_execute2 && sql_execute3 && sql_execute4 && sql_execute5 ) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}


$link->close();


?>




<?php

 /*$host="localhost:3306";
 $user="root";
 $password="";
 $db="project";*/

 $host="";
 $user="";
 $password="";
 $db="";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
$mysqli=new mysqli($host,$user,$password,$db);
$mysqli->set_charset("utf8");
} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}

?>

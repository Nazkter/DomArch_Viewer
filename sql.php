<?php
require_once 'config.php';
function connect(){
	$link = mysqli_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASS,MYSQL_DATABASE) or die("Error " . mysqli_error($link));
	return $link;
}
?>

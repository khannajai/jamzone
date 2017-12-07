<?php
$servername = "localhost";
$username = "jkhanna";
$password = NULL;
$dbname = "test";

$link = mysqli_connect($servername,$username,NULL,$dbname);

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>

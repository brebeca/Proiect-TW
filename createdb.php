<?php
$servername = "localhost";
$username = "root";
$password = "";

function db($dbname){
// Create database
$sql = "CREATE DATABASE $dbname";
if (mysqli_query($conn, $sql)) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . mysqli_error($conn);
}
}
// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
//db("produse_emag");
//db("produse_altex");
db("produse_cel");

mysqli_close($conn);
?>

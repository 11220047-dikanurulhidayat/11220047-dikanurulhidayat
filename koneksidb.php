<?php
$servername = "localhost";
$database = "11220047";
$username = "root";
$password = "";

// membuat koneksi
$conn = mysqli_connect($servername,$username,$password,$database);
//mengecek koneksi
if (!$conn) {
	die("koneksi gagal : " . mysql_connect_error());
	
}

?>
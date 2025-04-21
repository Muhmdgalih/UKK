<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "dally_list"; // Nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

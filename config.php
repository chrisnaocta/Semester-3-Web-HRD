<?php
// config.php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Kosongkan jika Anda tidak memiliki password untuk MySQL
$dbname = "db_lat_hrd";
// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
// Memeriksa koneksi
if ($conn->connect_error) {
 die("Koneksi gagal: " . $conn->connect_error);
}
?>
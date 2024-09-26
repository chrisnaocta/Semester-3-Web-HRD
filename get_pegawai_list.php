<?php
require 'config.php';

// Ambil data pegawai dari database
$pegawai = $conn->query("SELECT idpeg, nama FROM pegawai");

$options = '';
while ($row = $pegawai->fetch_assoc()) {
    $options .= "<option value='" . htmlspecialchars($row['idpeg']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
}

echo $options;
?>

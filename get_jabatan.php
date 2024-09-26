<?php
session_start();
require 'config.php';
require 'login_session.php';

if (isset($_POST['iddep'])) {
    $iddep = $_POST['iddep'];

    // Pastikan $iddep benar-benar ada dan merupakan string
    if (!empty($iddep)) {
        $query = "SELECT jabatan.idjab, jabatan.jabatan, departemen.departemen, departemen.iddep
        FROM jabatan
        JOIN departemen ON jabatan.iddep = departemen.iddep
        WHERE jabatan.iddep =?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $iddep); 
        $stmt->execute();
        $result = $stmt->get_result();

        // Tambahkan opsi jabatan ke dropdown
        echo "<option value=''>Pilih Jabatan</option>";
        while ($jabatan = $result->fetch_assoc()) {
            echo "<option value='" . $jabatan['idjab'] . "'>" . htmlspecialchars($jabatan['jabatan']) . "</option>";
        }
        // $stmt->close();
    } else {
        echo "<option value=''>Tidak ada jabatan tersedia</option>";
    }
}
?>
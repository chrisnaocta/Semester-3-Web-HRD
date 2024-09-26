<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Retrieve POST data
    $idpeg = $_POST['idpeg'];
    $iddep = $_POST['iddep'];
    $idjab = $_POST['idjab'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $gaji = $_POST['gaji'];
    $status = $_POST['status'];
    $jkelamin = $_POST['jkelamin'];
    $skerja = $_POST['skerja'];
    $cuti = $_POST['cuti'];
    $jenjangpendidikan = $_POST['jenjangpendidikan'];
    $tglkerja = $_POST['tglkerja'];
    $created_at = date('Y-m-d H:i:s'); // Using current timestamp for created_at

    // File upload handling
    $uploadDir = 'foto_peg/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/jfif'];

    // Get the uploaded file details
    $profilePeg = $_FILES['profile_peg'];
    $fileName = $profilePeg['name'];
    $fileTmp = $profilePeg['tmp_name'];
    $fileSize = $profilePeg['size'];
    $fileType = mime_content_type($fileTmp);

    // Check if the file is an image and within size limits (2MB in this example)
    if (in_array($fileType, $allowedTypes) && $fileSize <= 2 * 1024 * 1024) {
        // Create the destination path for the file (save with idpeg as the filename)
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); // Get the file extension (jpg, png)
        $newFileName = $idpeg . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        // Move the uploaded file to the destination folder
        if (move_uploaded_file($fileTmp, $destination)) {
            // Prepare the SQL statement (add profile picture field if necessary)
            $stmt = $conn->prepare("INSERT INTO pegawai (idpeg, iddep, idjab, nama, alamat, telepon, email, gaji, status, jkelamin, skerja, cuti, jenjangpendidikan, tglkerja, created_at, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Bind parameters (note: added 'foto' field)
            $stmt->bind_param("sssssssisssissss", $idpeg, $iddep, $idjab, $nama, $alamat, $telepon, $email, $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja, $created_at, $newFileName);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pegawai dan foto berhasil ditambahkan.'];
                header('Location: pegawai.php');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data pegawai.'];
            }

            // Close the statement
            $stmt->close();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupload foto pegawai.'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'File yang diupload harus berupa gambar JPG/PNG dan maksimal 2MB.'];
    }

    // Close the database connection
    $conn->close();
} else {
    header('Location: pegawai.php');
}
?>
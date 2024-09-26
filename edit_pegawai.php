<?php
session_start();
require 'config.php';
require 'login_session.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Ambil input dari form
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

    // Initialize variable to hold the new photo name
    $newPhotoName = null;

    // File upload handling
    if (isset($_FILES['profile_peg']) && $_FILES['profile_peg']['error'] == UPLOAD_ERR_OK) {
        // Define the upload directory and allowed file types
        $uploadDir = 'foto_peg/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/jfif'];
        $fileName = $_FILES['profile_peg']['name'];
        $fileTmp = $_FILES['profile_peg']['tmp_name'];
        $fileSize = $_FILES['profile_peg']['size'];
        $fileType = mime_content_type($fileTmp);

        // Check if the file is valid
        if (in_array($fileType, $allowedTypes) && $fileSize <= 2 * 1024 * 1024) {
            // Create a unique filename using idpeg
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newPhotoName = $idpeg . '.' . $fileExtension;
            $destination = $uploadDir . $newPhotoName;

            // Move the uploaded file to the destination folder
            if (!move_uploaded_file($fileTmp, $destination)) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupload foto pegawai.'];
                header('Location: pegawai.php');
                exit();
            }
        }else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'File yang diupload harus berupa gambar JPG/PNG/JPEG/JFIF dan maksimal 2MB.'];
            header('Location: pegawai.php');
            exit();
        }
    }

    // Prepare the SQL update statement
    if ($newPhotoName) {
        // If there's a new photo, update it in the database
        $stmt = $conn->prepare("UPDATE pegawai
        SET iddep=?, idjab=?, nama=?, alamat=?, telepon=?, email=?, gaji=?, status=?, jkelamin=?, skerja=?,
        cuti=?, jenjangpendidikan=?, tglkerja=?, foto=? WHERE idpeg=?");
        $stmt->bind_param('ssssssissssssss', $iddep, $idjab, $nama, $alamat, $telepon, $email,
        $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja, $newPhotoName, $idpeg);
    } else {
        // If no new photo, do not update the photo field
        $stmt = $conn->prepare("UPDATE pegawai
        SET iddep=?, idjab=?, nama=?, alamat=?, telepon=?, email=?, gaji=?, status=?, jkelamin=?, skerja=?,
        cuti=?, jenjangpendidikan=?, tglkerja=? WHERE idpeg=?");
        $stmt->bind_param('ssssssisssssss', $iddep, $idjab, $nama, $alamat, $telepon, $email,
        $gaji, $status, $jkelamin, $skerja, $cuti, $jenjangpendidikan, $tglkerja, $idpeg);
    }

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pegawai berhasil diperbarui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbarui data.'];
    }

    $stmt->close();
    header('Location: pegawai.php');
    exit();
    }else{
    header("Location: pegawai.php");
    exit();
}
?>
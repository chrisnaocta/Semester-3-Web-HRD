<?php
session_start();
require 'config.php';
require 'login_session.php';

$iduser = $_SESSION['iduser'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $password = $_POST['password'];

    // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT username, password, foto FROM login WHERE iduser = ?");
    $stmt->bind_param("i", $iduser);
    $stmt->execute();
    $stmt->bind_result($username, $db_password, $foto);
    $stmt->fetch();
    $stmt->close();

    //Verify the password
    if (password_verify($password, $db_password)){
        if ($_FILES['profile-picture']) {
            $fileName = $fileSize = $_FILES['profile-picture']['name'];
            $fileSize = $_FILES['profile-picture']['size'];
            $tmpName = $_FILES['profile-picture']['tmp_name'];

            $validExtensions = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $fileName);
            $imageExtension = strtolower(end($imageExtension));
            if(!in_array($imageExtension, $validExtensions)) {
                $_SESSION['message'] = ['type' => 'error','text'=> 'Invalid image extension.'];
                header('Location: profile.php');
                exit();
            } else if ($fileSize > 1000000) {
                $_SESSION['message'] = ['type' => 'error','text'=> 'File size is too large.'];
                header('Location: profile.php');
                exit();
            } else {
                $imageName = $username . "." . $imageExtension;

                if ($foto) {
                    unlink('foto/' . $foto);
                }
                if (!move_uploaded_file($tmpName, 'foto/' . $imageName)) {
                    $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat memperarui data.'];
                    header('Location: profile.php');
                    exit();
                }
                
                $stmt = $conn->prepare("UPDATE login SET foto = ? WHERE iduser = ?");
                $stmt->bind_param("si", $imageName, $iduser);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $_SESSION['message'] = ['type' => 'success','text'=> 'Data user berhasil diperbarui.'];
                } else {
                    $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi kesalahan saat memperarui data.'];
                }
            
                $stmt->close();
                header('Location: profile.php');
                exit();
            }
        }
    } else {
        $_SESSION['message'] = ['type' => 'error','text'=> 'Password salah tidak bisa update foto.'];
        header("Location: profile.php");
        exit(); 
    }
}else{
    header("Location: profile.php");
    exit();
}
?>
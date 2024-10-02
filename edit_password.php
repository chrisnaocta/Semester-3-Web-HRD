<?php
session_start();
require 'config.php';
require 'login_session.php';

$iduser = $_SESSION['iduser'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $old = $_POST['current-password'];
    $new = $_POST['new-password'];
    $reentered = $_POST['re-entered-password'];

    if ($new !== $reentered) {
        $_SESSION['message'] = ['type' => 'error','text'=> 'New password dan re-entered password berbeda.'];
        header("Location: profile.php");
        exit(); 
    } else {
        // Hash the password
        $hashed_password = password_hash($new, PASSWORD_DEFAULT);
    }

    // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT password FROM login WHERE iduser = ?");
    $stmt->bind_param("i", $iduser);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    //Verify the password
    if (password_verify($old, $db_password)){
        $stmt = $conn->prepare("UPDATE login SET password=? WHERE iduser=?");
        $stmt->bind_param("si", $hashed_password, $iduser);
        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['message'] = ['type' => 'success','text'=> 'Password berhasil diganti.'];
            header("Location: profile.php");
            exit(); 
        } else {
            $stmt->close();
            $_SESSION['message'] = ['type' => 'error','text'=> 'Terjadi error dalam mengganti password.'];
            header("Location: profile.php");
            exit(); 
        }
        
    } else {
        $_SESSION['message'] = ['type' => 'error','text'=> 'Current password tidak sesuai.'];
        header("Location: profile.php");
        exit(); 
    }
}else{
    header("Location: profile.php");
    exit();
}
?>
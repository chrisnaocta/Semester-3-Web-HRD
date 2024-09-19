<?php  
if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}
?>
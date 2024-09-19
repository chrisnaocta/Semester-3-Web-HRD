<?php  
if (($_SESSION['iduser'])) {
    header("Location: index.php");
    exit();
}
?>
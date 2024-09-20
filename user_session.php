<?php
// user_session.php

session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION['iduser'])) {
    // If user is logged in, redirect them to the index.php page
    header("Location: index.php");
    exit();
}
?>
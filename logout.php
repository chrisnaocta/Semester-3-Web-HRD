<?php
// logout.php
session_start();
require 'login_session.php';
// Hapus semua data sesi
session_unset();
session_destroy();
// Redirect ke halaman login
header('Location: login.php');
exit();
?>
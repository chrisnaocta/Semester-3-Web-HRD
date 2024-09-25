<?php
session_start();
require 'config.php';

// Query to count the number of rows in the 'namausaha' table
$result = $conn->query("SELECT COUNT(*) as total FROM namausaha");
$row = $result->fetch_assoc();

// Return the result as JSON
echo json_encode($row);
?>
<?php
require 'config.php';

function query($query){
    global $result, $conn;
    $result = mysqli_query($conn, $query);
    $rows =[];
    while ($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}
?>
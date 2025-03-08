<?php
// Database connection
$db = mysqli_connect('localhost', 'root', '', 'cdlp_assessment');

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo "Connected successfully";
}
?>
<?php
include 'dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
header('Content-Type: application/json');
echo getAllAnnouncements($conn);
?>

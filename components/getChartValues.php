<?php
require './dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

$values = countPurpose($conn);
echo json_encode($values);
?>
<?php
require './dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

$values = countLab($conn);
echo json_encode($values);
?>
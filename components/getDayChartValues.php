<?php
require './dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

$values = countByDayOfWeek($conn);
echo json_encode($values);
?>
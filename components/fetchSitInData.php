<?php
require './dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

// Get the type parameter from the request
$type = $_GET['type'] ?? 'sit-in';

if ($type == 'sit-in') {
    $SitInList = getAllSitIn($conn);
} elseif ($type == 'current') {
    $SitInList = getAllSitInCurrentAdmin($conn);
} elseif ($type == 'history') {
    $SitInList = getAllSitInHistoryAdmin($conn);
} else {
    $SitInList = [];
}

echo json_encode($SitInList);
?>

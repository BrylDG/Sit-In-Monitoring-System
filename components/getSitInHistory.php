<?php
require './dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get parameters from request
$purpose = isset($_GET['purpose']) ? $_GET['purpose'] : "All";
$lab = isset($_GET['lab']) ? $_GET['lab'] : "All";


$response = [
    getAllSitInHistoryAdminData($conn, $lab, $purpose)
];

header('Content-Type: application/json');
echo json_encode($response);
?>

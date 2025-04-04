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
    "purpose_counts" => generatePurpose($conn, $purpose), // Updated function with filtering
    "lab_counts" => generateLab($conn, $lab), // Modify countLab to accept a lab filter
    "day_counts" => countByDayOfWeek($conn) // No changes needed
];

header('Content-Type: application/json');
echo json_encode($response);
?>

<?php
require './dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get reset type from request
$resetType = $_GET['resetType'] ?? "default"; 

// Call function to reset sessions
$result = resetSessions($conn, $resetType);

if ($result) {
    echo json_encode(["success" => true, "message" => "Sessions reset successfully"]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to reset sessions"]);
}
?>

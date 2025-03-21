<?php
include 'dbFunctions.php'; // ✅ Ensure the correct path

header('Content-Type: application/json');

// ✅ Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function updateSitInTable($idNo, $firstName, $middleName, $lastName, $purpose, $lab, $conn) {
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $studentName = trim("$firstName $middleName $lastName");

    // ✅ Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO SitInTable (idNo, student_name, purpose, lab) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['error' => 'Database query failed: ' . $conn->error]); // ✅ Show SQL error
        exit;
    }

    $stmt->bind_param("ssss", $idNo, $studentName, $purpose, $lab);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Sit-in record added successfully']);
    } else {
        echo json_encode(['error' => 'Failed to insert record: ' . $stmt->error]); // ✅ Show detailed error
    }

    $stmt->close();
}

// ✅ Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idNo = $_POST['idNo'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $lab = $_POST['lab'] ?? '';

    updateSitInTable($idNo, $firstName, $middleName, $lastName, $purpose, $lab, $conn);
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
?>

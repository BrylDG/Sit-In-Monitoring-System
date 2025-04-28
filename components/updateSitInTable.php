<?php
include 'dbFunctions.php'; // Ensure the correct path

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function updateSitInTable($idNo, $firstName, $middleName, $lastName, $purpose, $lab, $conn) {
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    // First check if the lab is open
    $checkLabQuery = $conn->prepare("SELECT status FROM labschedules WHERE lab_no = ?");
    if (!$checkLabQuery) {
        echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
        exit;
    }

    $checkLabQuery->bind_param("i", $lab);
    $checkLabQuery->execute();
    $checkLabQuery->store_result();

    if ($checkLabQuery->num_rows === 0) {
        echo json_encode(['error' => 'Lab not found']);
        $checkLabQuery->close();
        exit;
    }

    $checkLabQuery->bind_result($labStatus);
    $checkLabQuery->fetch();
    $checkLabQuery->close();

    if ($labStatus !== 'Open') {
        echo json_encode(['error' => 'Lab is currently closed. Cannot add sit-in record.']);
        exit;
    }

    $studentName = trim("$firstName $middleName $lastName");

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO SitInTable (idNo, student_name, purpose, lab) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssss", $idNo, $studentName, $purpose, $lab);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Sit-in record added successfully']);
    } else {
        echo json_encode(['error' => 'Failed to insert record: ' . $stmt->error]);
    }

    $stmt->close();
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idNo = $_POST['idNo'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $lab = $_POST['lab'] ?? '';

    // Validate lab is numeric
    if (!is_numeric($lab)) {
        echo json_encode(['error' => 'Invalid lab number']);
        exit;
    }

    updateSitInTable($idNo, $firstName, $middleName, $lastName, $purpose, $lab, $conn);
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
?>
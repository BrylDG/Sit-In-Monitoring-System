<?php
include 'dbFunctions.php'; // ✅ Ensure the correct database connection

header('Content-Type: application/json');

// ✅ Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function searchUser($searchTerm, $conn) { 
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";

    // ✅ Fetch additional fields: idNo, lastName, firstName, middleName, course, yearLevel, email, profileImage
    $stmt = $conn->prepare("
        SELECT idNo, lastName, firstName, middleName, course, yearLevel, email, profileImage 
        FROM users 
        WHERE idNo LIKE ? OR firstName LIKE ? OR lastName LIKE ?
    ");

    if (!$stmt) {
        echo json_encode(['error' => 'Database query failed']);
        exit;
    }

    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'idNo'       => htmlspecialchars($row['idNo']),
            'lastName'   => htmlspecialchars($row['lastName']),
            'firstName'  => htmlspecialchars($row['firstName']),
            'middleName' => htmlspecialchars($row['middleName']),
            'course'     => htmlspecialchars($row['course']),
            'yearLevel'  => htmlspecialchars($row['yearLevel']),
            'email'      => htmlspecialchars($row['email']),
            'profileImage' => htmlspecialchars($row['profileImage'])
        ];
    }

    $stmt->close();
    echo json_encode($users);
    exit;
}

// ✅ Check if request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    searchUser($_POST['query'], $conn);
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
?>

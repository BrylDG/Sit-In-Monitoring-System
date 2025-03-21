<?php
require '../components/dbFunctions.php'; // ✅ Ensure correct path
header('Content-Type: application/json');

// ✅ Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Check if database connection exists
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// ✅ Validate POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idNo']) && isset($_POST['feedback']) && isset($_POST['name']) && isset($_POST['lab'])) {
    $idNo = trim($_POST['idNo']);
    $feedback = trim($_POST['feedback']);
    $name = trim($_POST['name']);
    $lab = trim($_POST['lab']);

    // ✅ Check if values are empty
    if (empty($idNo) || empty($feedback) || empty($name) || empty($lab)) {
        echo json_encode(["error" => "All fields are required"]);
        exit;
    }

    // ✅ Generate a random 9-digit feedback number
    $feedbackNo = mt_rand(10000000, 99999999); // Generates a number between 100000000 - 999999999

    // ✅ Start transaction
    $conn->begin_transaction();

    try {
        // ✅ Insert feedback into feedback table
        $stmt = $conn->prepare("INSERT INTO feedback (feedbackNo, idNo, feedback, lab, name) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing insert statement: " . $conn->error);
        }
        $stmt->bind_param("sssss", $feedbackNo, $idNo, $feedback, $lab, $name);
        if (!$stmt->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt->error);
        }
        $stmt->close();

        // ✅ Update feedbackNo in SitInHistory table
        $stmt = $conn->prepare("UPDATE SitInHistory SET feedbackNo = ? WHERE idNo = ?");
        if (!$stmt) {
            throw new Exception("Error preparing update statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $feedbackNo, $idNo);
        if (!$stmt->execute()) {
            throw new Exception("Error executing update statement: " . $stmt->error);
        }
        $stmt->close();

        // ✅ Commit transaction
        $conn->commit();

        echo json_encode(["success" => "Feedback submitted successfully!", "feedbackNo" => $feedbackNo]);
        exit;

    } catch (Exception $e) {
        // ❌ Rollback if any error occurs
        $conn->rollback();
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>

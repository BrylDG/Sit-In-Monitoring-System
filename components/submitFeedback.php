<?php
require '../components/dbFunctions.php';
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['idNo'], $_POST['feedback'], $_POST['name'], $_POST['lab'], $_POST['purpose'])
) {
    $idNo = trim($_POST['idNo']);
    $feedback = trim($_POST['feedback']);
    $name = trim($_POST['name']);
    $lab = trim($_POST['lab']);
    $purpose = trim($_POST['purpose']);

    if (empty($idNo) || empty($feedback) || empty($name) || empty($lab) || empty($purpose)) {
        echo json_encode([
            "error" => "All fields are required",
            "idNo" => $idNo,
            "lab" => $lab,
            "purpose" => $purpose
        ]);
        exit;
    }

    // ✅ Basic list of explicit/bad words
    $badWords = ['fuck', 'shit', 'bitch', 'asshole', 'bastard', 'damn', 'crap', 'dick', 'piss', 'slut', 'yawa', 'pisti', 'boang', 'bogo'];

    $explicit = 'no'; // Default
    foreach ($badWords as $word) {
        if (preg_match("/\b" . preg_quote($word, '/') . "\b/i", $feedback)) {
            $explicit = 'yes';
            break;
        }
    }

    // ✅ Generate feedback number
    $feedbackNo = mt_rand(100000000, 999999999);

    $conn->begin_transaction();

    try {
        // ✅ Insert feedback
        $stmt = $conn->prepare("INSERT INTO feedback (feedbackNo, idNo, feedback, lab, purpose, name, explicit) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing insert statement: " . $conn->error);
        }
        $stmt->bind_param("sssssss", $feedbackNo, $idNo, $feedback, $lab, $purpose, $name, $explicit);
        if (!$stmt->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt->error);
        }
        $stmt->close();

        // ✅ Update SitInHistory with feedback number
        $stmt = $conn->prepare("UPDATE SitInHistory SET feedbackNo = ? WHERE idNo = ? AND lab = ? AND purpose = ?");
        if (!$stmt) {
            throw new Exception("Error preparing update statement: " . $conn->error);
        }
        $stmt->bind_param("ssss", $feedbackNo, $idNo, $lab, $purpose);
        if (!$stmt->execute()) {
            throw new Exception("Error executing update statement: " . $stmt->error);
        }
        if ($stmt->affected_rows === 0) {
            throw new Exception("No matching SitInHistory record found to update.");
        }
        $stmt->close();

        $conn->commit();

        echo json_encode([
            "success" => "Feedback submitted successfully!",
            "feedbackNo" => $feedbackNo,
            "explicit" => $explicit
        ]);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            "error" => $e->getMessage(),
            "idNo" => $idNo,
            "lab" => $lab,
            "purpose" => $purpose
        ]);
        exit;
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}

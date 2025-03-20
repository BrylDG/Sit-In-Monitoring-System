<?php
require '../components/dbFunctions.php';
header('Content-Type: application/json');

if (isset($_GET['feedbackNo'])) {
    $feedbackNo = $_GET['feedbackNo'];

    $stmt = $conn->prepare("SELECT feedback FROM feedback WHERE feedbackNo = ?");
    if (!$stmt) {
        echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $feedbackNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "feedback" => $row['feedback']]);
    } else {
        echo json_encode(["error" => "No feedback found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing feedbackNo"]);
}
?>

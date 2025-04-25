<?php
$conn = new mysqli("localhost", "root", "", "sitinmonitoringsystem");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["student_id"])) {
    $student_id = intval($_POST["student_id"]);

    // Modified query to add 1 to both points and TotalPoints
    $query = "UPDATE sessions SET points = points + 1, TotalPoints = TotalPoints + 1 WHERE idNo = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(["success" => false, "error" => $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No rows updated. Check if student exists."]);
    }

    $stmt->close();
    $conn->close();
}
?>

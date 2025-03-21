<?php
include 'dbFunctions.php'; // ✅ Ensure correct path

header('Content-Type: application/json');

// ✅ Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Check if this is a valid request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idNo'])) {
    $idNo = $_POST['idNo'];

    // ✅ Check if database connection works
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    // ✅ Start transaction
    $conn->begin_transaction();

    try {
        // ✅ Step 1: Retrieve the Sit-In record before deletion
        $stmt = $conn->prepare("SELECT idNo, student_name, purpose, lab, SitInTime FROM SitInTable WHERE idNo = ?");
        if (!$stmt) {
            throw new Exception("Error preparing select statement: " . $conn->error);
        }
        $stmt->bind_param("s", $idNo);
        if (!$stmt->execute()) {
            throw new Exception("Error executing select statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $sitInData = $result->fetch_assoc();
        $stmt->close();

        if (!$sitInData) {
            throw new Exception("No matching record found for idNo: $idNo");
        }

        // ✅ Step 2: Generate an 8-digit `historyID`
        $historyID = mt_rand(10000000, 99999999); // Generates a number between 10000000 - 99999999

        // ✅ Step 3: Split `SitInTime` into `date` and `login`
        $sitInDateTime = new DateTime($sitInData['SitInTime']);
        $date = $sitInDateTime->format('Y-m-d'); // Extract DATE (YYYY-MM-DD)
        $loginTime = $sitInDateTime->format('H:i:s'); // Extract TIME (HH:MM:SS)

        // ✅ Step 4: Insert into SitInHistory
        $stmt = $conn->prepare("INSERT INTO SitInHistory (historyID, idNo, name, purpose, lab, login, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing insert statement: " . $conn->error);
        }
        $stmt->bind_param("sssssss", $historyID, $sitInData['idNo'], $sitInData['student_name'], $sitInData['purpose'], $sitInData['lab'], $loginTime, $date);
        if (!$stmt->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt->error);
        }
        $stmt->close();

        // ✅ Step 5: Delete row from SitInTable
        $stmt = $conn->prepare("DELETE FROM SitInTable WHERE idNo = ?");
        if (!$stmt) {
            throw new Exception("Error preparing delete statement: " . $conn->error);
        }
        $stmt->bind_param("s", $idNo);
        if (!$stmt->execute()) {
            throw new Exception("Error executing delete statement: " . $stmt->error);
        }
        $stmt->close();

        // ✅ Step 6: Deduct 1 from sessions in the sessions table (ensure it doesn’t go below 0)
        $stmt = $conn->prepare("UPDATE sessions SET sessions = GREATEST(sessions - 1, 0) WHERE idNo = ?");
        if (!$stmt) {
            throw new Exception("Error preparing update statement: " . $conn->error);
        }
        $stmt->bind_param("s", $idNo);
        if (!$stmt->execute()) {
            throw new Exception("Error executing update statement: " . $stmt->error);
        }
        $stmt->close();

        // ✅ Commit transaction
        $conn->commit();
        echo json_encode(['success' => 'User signed out successfully, sessions updated, and record moved to SitInHistory', 'historyID' => $historyID]);
        exit;

    } catch (Exception $e) {
        // ❌ Rollback if any error occurs
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid request: Missing idNo']);
    exit;
}
?>

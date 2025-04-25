<?php
include 'dbFunctions.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idNo'])) {
    $idNo = $_POST['idNo'];

    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $conn->begin_transaction();

    try {
        // 1. Get the sit-in record
        $stmt = $conn->prepare("SELECT idNo, student_name, purpose, lab, SitInTime FROM SitInTable WHERE idNo = ?");
        if (!$stmt) throw new Exception("Error preparing select statement: " . $conn->error);
        $stmt->bind_param("s", $idNo);
        if (!$stmt->execute()) throw new Exception("Error executing select statement: " . $stmt->error);
        $sitInData = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$sitInData) throw new Exception("No matching record found for idNo: $idNo");

        // 2. Generate historyID
        $historyID = mt_rand(10000000, 99999999);

        // 3. Prepare date and login time
        $sitInDateTime = new DateTime($sitInData['SitInTime']);
        $date = $sitInDateTime->format('Y-m-d');
        $loginTime = $sitInDateTime->format('H:i:s');

        // 4. First insert into SitInHistory (logout will be auto-set by DB)
        $stmt = $conn->prepare("INSERT INTO SitInHistory 
                              (historyID, idNo, name, purpose, lab, login, date) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) throw new Exception("Error preparing insert statement: " . $conn->error);
        $stmt->bind_param("sssssss", $historyID, $sitInData['idNo'], $sitInData['student_name'], 
                         $sitInData['purpose'], $sitInData['lab'], $loginTime, $date);
        if (!$stmt->execute()) throw new Exception("Error executing insert statement: " . $stmt->error);
        $stmt->close();

        // 5. Now update duration in HH:MM:SS format
        $updateStmt = $conn->prepare("UPDATE SitInHistory 
                                    SET duration = TIME_FORMAT(
                                        SEC_TO_TIME(
                                            TIMESTAMPDIFF(
                                                SECOND, 
                                                CONCAT(date, ' ', login), 
                                                logout
                                            )
                                        ), 
                                        '%H:%i:%s'
                                    )
                                    WHERE historyID = ?");
        if (!$updateStmt) throw new Exception("Error preparing duration update: " . $conn->error);
        $updateStmt->bind_param("s", $historyID);
        if (!$updateStmt->execute()) throw new Exception("Error updating duration: " . $updateStmt->error);
        $updateStmt->close();

        // 6. Get the complete record with calculated values
        $result = $conn->query("SELECT login, logout, duration FROM SitInHistory WHERE historyID = '$historyID'");
        $historyData = $result->fetch_assoc();

        // 7. Delete from SitInTable
        $stmt = $conn->prepare("DELETE FROM SitInTable WHERE idNo = ?");
        if (!$stmt) throw new Exception("Error preparing delete statement: " . $conn->error);
        $stmt->bind_param("s", $idNo);
        if (!$stmt->execute()) throw new Exception("Error executing delete statement: " . $stmt->error);
        $stmt->close();

        // 8. Update sessions
        $stmt = $conn->prepare("UPDATE sessions SET sessions = GREATEST(sessions - 1, 0) WHERE idNo = ?");
        if (!$stmt) throw new Exception("Error preparing update statement: " . $conn->error);
        $stmt->bind_param("s", $idNo);
        if (!$stmt->execute()) throw new Exception("Error executing update statement: " . $stmt->error);
        $stmt->close();

        $conn->commit();
        echo json_encode([
            'success' => true,
            'historyID' => $historyID,
            'login_time' => $sitInData['SitInTime'],
            'logout_time' => $historyData['logout'],
            'duration' => $historyData['duration']
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Transaction failed: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request: Missing idNo']);
}
?>
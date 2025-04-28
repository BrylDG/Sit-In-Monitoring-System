<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "sitinmonitoringsystem");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s');

// Handle status update via AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lab_no = intval($_POST["lab_no"]);
    $status = $_POST["status"];

    if ($status == "Closed") {
        $query = "UPDATE labschedules SET status = ?, manually_closed = 1 WHERE lab_no = ?";
    } else {
        $query = "UPDATE labschedules SET status = ?, manually_closed = 0 WHERE lab_no = ?";
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $status, $lab_no);
    $stmt->execute();

    if ($stmt->error) {
        echo "Execution failed: " . $stmt->error;
    } else {
        echo "Status updated.";
    }
    exit;
}

// Auto-update status logic
// Update to Open if current time is within open hours and not manually closed
$conn->query("
    UPDATE labschedules 
    SET status = 'Open', manually_closed = 0 
    WHERE manually_closed = 0 AND TIME('$currentTime') >= open_time AND TIME('$currentTime') <= close_time
");

// Update to Closed if current time is outside open hours and not manually opened
$conn->query("
    UPDATE labschedules 
    SET status = 'Closed' 
    WHERE manually_closed = 0 AND (TIME('$currentTime') < open_time OR TIME('$currentTime') > close_time)
");

// Fetch lab schedules for display
$result = $conn->query("SELECT *, 
                        DATE_FORMAT(open_time, '%h:%i %p') AS formatted_open_time,
                        DATE_FORMAT(close_time, '%h:%i %p') AS formatted_close_time
                        FROM labschedules");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<div class="labs-container">
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="lab-card" onclick="openModal(<?= $row['lab_no'] ?>)">
            <div class="top-div">
                <h3>Lab <?= $row['lab_no'] ?></h3>
                <p class="status <?= $row['status'] ?>"><?= $row['status'] ?></p>
            </div>
            <div class="bottom-div">
                <p><strong>Open:</strong> <?= $row['formatted_open_time'] ?></p><br>
                <p><strong>Close:</strong> <?= $row['formatted_close_time'] ?></p>
            </div>
        </div>

        <!-- Modal for this lab -->
        <div id="modal-<?= $row['lab_no'] ?>" class="modal1">
            <div class="modal-content">
                <h3>Update Status for Lab <?= $row['lab_no'] ?></h3>
                <select id="status-<?= $row['lab_no'] ?>">
                    <option value="Open" <?= $row['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
                    <option value="Closed" <?= $row['status'] == 'Closed' ? 'selected' : '' ?>>Closed</option>
                </select>
                <button onclick="saveStatus(<?= $row['lab_no'] ?>)">Save</button>
            </div>
        </div>
    <?php } ?>
</div>
<?php
$conn = new mysqli("localhost", "root", "", "sitinmonitoringsystem");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lab_no = intval($_POST["lab_no"]);
    $status = $_POST["status"];

    if ($status == "Closed") {
        // Manually closed
        $stmt = $conn->prepare("UPDATE labschedules SET status = ?, manually_closed = 1 WHERE lab_no = ?");
    } else {
        // Reopened manually, remove manual lock
        $stmt = $conn->prepare("UPDATE labschedules SET status = ?, manually_closed = 0 WHERE lab_no = ?");
    }

    $stmt->bind_param("si", $status, $lab_no);
    $stmt->execute();

    echo "Status updated.";
}
?>

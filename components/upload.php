<?php
$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES['file']['name']);
    $targetFile = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>

<?php
$uploadDir = "uploads/";

if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filePath = $uploadDir . $filename;

    if (file_exists($filePath)) {
        unlink($filePath);
        echo "success";
    } else {
        echo "error";
    }
}
?>

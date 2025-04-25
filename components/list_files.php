<?php
$uploadDir = "uploads/";
$files = array_diff(scandir($uploadDir), array('.', '..'));
echo json_encode(array_values($files));
?>

<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
?>

<div class="container">
  <h2>Upload a file</h2>
  <input type="file" id="fileInput">
  <button class="upload-btn">Upload</button>

  <h3>Uploaded Files:</h3>
  <div class="file-list" id="fileList"></div>
</div>
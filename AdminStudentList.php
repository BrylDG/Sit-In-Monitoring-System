<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
fetchStudents($conn);
$conn->close();
?>

<div class="studentListContainer">
    <div class="studentContainer">

    </div>
</div>

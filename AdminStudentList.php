<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
?>

<div class="studentListContainer">
    <div class="studentContainer">
        <?php
                fetchStudents($conn);
        ?>
    </div>
</div>

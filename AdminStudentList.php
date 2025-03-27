<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
?>

<div class="studentListContainer">
        <?php
                fetchStudents($conn);
        ?>
</div>

<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
?>

<div class="studentListContainer">
        <?php
                fetchStudents($conn);
        ?>
<button class="reset-btn" value="All">Reset All Sessions</button>'
</div>

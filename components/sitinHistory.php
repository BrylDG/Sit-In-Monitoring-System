<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$HistoryList = json_decode(getSitInHistory($conn), true); // Sample function name

if (!empty($HistoryList)) {
    foreach ($HistoryList as $history) {
        ?>
        <div class="SitInCard">
            <div class="sitinSection">
                <label>ID No: </label>
                <h3 class="sitinDetail"><?php echo htmlspecialchars($history['idNo']); ?></h3>
            </div>
            <div class="sitinSection">
                <label class="sitinLabel">Name: </label>
                <p class="sitinDetail"><?php echo nl2br(htmlspecialchars($history['student_name'])); ?></p>
            </div>
            <div class="sitinSection">
                <label class="sitinLabel">Signed Out At: </label>
                <p class="sitinDetail"><?php echo nl2br(htmlspecialchars($history['signOutTime'])); ?></p>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p>No History Available.</p>';
}
?>

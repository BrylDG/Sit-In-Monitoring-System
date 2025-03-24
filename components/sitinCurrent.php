<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$SitInList = json_decode(getAllSitIn($conn), true);

if (!empty($SitInList)) {
    foreach ($SitInList as $SitInlist) {
        ?>
        <div class="SitInCard" id="sitIn-<?php echo $SitInlist['idNo']; ?>">
            <div class="sitinSection">
                <label>ID No: </label>
                <h3 class="sitinDetail"><?php echo htmlspecialchars($SitInlist['idNo']); ?></h3>
            </div>
            <div class="sitinSection">
                <label class="sitinLabel">Name: </label>
                <p class="sitinDetail"><?php echo nl2br(htmlspecialchars($SitInlist['student_name'])); ?></p>
            </div>
            <div class="sitinSection">
                <label class="sitinLabel">Purpose: </label>
                <p class="sitinDetail"><?php echo nl2br(htmlspecialchars($SitInlist['purpose'])); ?></p>
            </div>
            <div class="sitinSection">
                <label class="sitinLabel">Lab: </label>
                <p class="sitinDetail"><?php echo nl2br(htmlspecialchars($SitInlist['lab'])); ?></p>
            </div>
            <hr>
            <div class="sitinBottom">
                <small><?php echo $SitInlist['SitInTime']; ?></small>
                <button class="signout-btn" data-id="<?php echo $SitInlist['idNo']; ?>">Sign Out</button>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p>No Sit-In.</p>';
}
?>

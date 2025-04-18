<?php

require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$userData = getProfile($conn);
if (!isset($userData['idNo'])) {
    die("Error: User ID not found.");
}
$idNo = $userData['idNo']; // Get the logged-in user's ID
$HistoryList = json_decode(getAllSitInHistoryAdmin($conn), true);

?>

<div class="userHistoryDiv">
    <div class="feedbackContainer">
        <?php if (!empty($HistoryList)): ?>
            <?php foreach ($HistoryList as $Historylist): ?>
                <div class="announcement-item" id="sitIn-<?php echo $Historylist['idNo']; ?>">
                    <h3><?php echo htmlspecialchars($Historylist['idNo']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($Historylist['name'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($Historylist['purpose'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($Historylist['lab'])); ?></p>
                    <small><?php echo $Historylist['login']; ?></small>
                    <small><?php echo $Historylist['date']; ?></small>
                    <small><?php echo $Historylist['logout']; ?></small>
                    <br>
                    <button class="feedback-btn" data-id="<?php echo $Historylist['idNo']; ?>" data-name="<?php echo $Historylist['name']; ?>" data-lab="<?php echo $Historylist['lab']; ?>" data-feedbackNo="<?php echo $Historylist['feedbackNo']; ?>">Feedback</button>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Sit-In.</p>
        <?php endif; ?>
    </div>
</div>

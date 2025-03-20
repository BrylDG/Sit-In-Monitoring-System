<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$HistoryList = json_decode(getAllSitInHistory($conn), true);
?>

<div class="userHistoryDiv">
    <div class="historyContainer">
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
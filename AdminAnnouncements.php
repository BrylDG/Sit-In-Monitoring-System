<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$announcements = json_decode(getAllAnnouncements($conn), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $announcementTitle = trim($_POST['announcementTitle']);
    $announcementDetails = trim($_POST['announcementContent']);

    addAnnouncement($conn, $announcementTitle, $announcementDetails);
}

$conn->close();

?>
<div class="AnnouncementsDiv">
    <div id="top-div">
        <button id="addAnnouncement">+ Add Announcement</button>
    </div>

    <div id="bottom-div">
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $announcement): ?>
                <div class="announcement-item">
                    <h3><?php echo htmlspecialchars($announcement['announcement_title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($announcement['announcement_details'])); ?></p>
                    <small>Posted on: <?php echo $announcement['created_on']; ?></small>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No announcements available.</p>
        <?php endif; ?>
    </div>

</div>
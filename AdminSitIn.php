<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$SitInList = json_decode(getAllSitIn($conn), true);
$HistoryListAdmin = json_decode(getAllSitInHistoryAdmin($conn), true);
$DailyListAdmin = json_decode(getAllSitInDailyHistoryAdmin($conn), true);
?>

<div class="SitInDiv">
    <div class="top">
        <form id="searchForm">
            <input type="text" id="searchInput" name="query" placeholder="Search users...">
            <div id="searchResults"></div>
        </form>
        <button class="download" id='history'>Download Reports</button>
    </div>
    <div class="bottom">
        <?php if (!empty($SitInList)): ?>
            <?php foreach ($SitInList as $SitInlist): ?>
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
                        <button class="signout-btn" 
        data-id="<?php echo $SitInlist['idNo']; ?>" 
        data-student-id="<?php echo $SitInlist['idNo']; ?>">Sign Out</button>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Sit-In.</p>
        <?php endif; ?>
    </div>

    <div class="divider">
        <hr class="divider-line"><h1 id="divider-title">Daily Sit-In</h1><hr class="divider-line">
    </div>

    <div class="History">
        <div class="history-labels">
            <p>History Id</p>
            <p>Id No</p>
            <p>Name</p>
            <p>Purpose</p>
            <p>Lab</p>
            <p>Logged In</p>
            <p>Logged Out</p>
            <p>Session Date</p>
        </div>

        <div class="historyContainer">
            <?php if (!empty($DailyListAdmin)): ?>
                <?php foreach ($DailyListAdmin as $DailyListAdmin): ?>
                    <div class="history-log" id="sitIn-<?php echo $DailyListAdmin['idNo']; ?>">
                        <h3 style="margin-right: 7%;"><?php echo htmlspecialchars($DailyListAdmin['historyID']); ?></h3>
                        <p style="margin-right: 7%;"><?php echo nl2br(htmlspecialchars($DailyListAdmin['idNo'])); ?></p>
                        <p style="margin-right: 10%;"><?php echo nl2br(htmlspecialchars($DailyListAdmin['name'])); ?></p>
                        <p style="margin-right: 12%;"><?php echo nl2br(htmlspecialchars($DailyListAdmin['purpose'])); ?></p>
                        <p style="margin-right: 10%;"><?php echo nl2br(htmlspecialchars($DailyListAdmin['lab'])); ?></p>
                        <small style="margin-right: 11%;"><?php echo $DailyListAdmin['login']; ?></small>
                        <small style="margin-right: 11%;"><?php echo $DailyListAdmin['logout']; ?></small>
                        <small><?php echo $DailyListAdmin['date']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Sit-In.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="divider">
        <hr class="divider-line"><h1 id="divider-title">Sit-In History</h1><hr class="divider-line">
    </div>

    <div class="History">
        <div class="history-labels">
            <p>History Id</p>
            <p>Id No</p>
            <p>Name</p>
            <p>Purpose</p>
            <p>Lab</p>
            <p>Logged In</p>
            <p>Logged Out</p>
            <p>Session Date</p>
        </div>
        
        <div class="historyContainer">
            <?php if (!empty($HistoryListAdmin)): ?>
                <?php foreach ($HistoryListAdmin as $HistoryListAdmin): ?>
                    <div class="history-log" id="sitIn-<?php echo $HistoryListAdmin['idNo']; ?>">
                        <h3 style="margin-right: 7%;"><?php echo htmlspecialchars($HistoryListAdmin['historyID']); ?></h3>
                        <p style="margin-right: 7%;"><?php echo nl2br(htmlspecialchars($HistoryListAdmin['idNo'])); ?></p>
                        <p style="margin-right: 10%;"><?php echo nl2br(htmlspecialchars($HistoryListAdmin['name'])); ?></p>
                        <p style="margin-right: 12%;"><?php echo nl2br(htmlspecialchars($HistoryListAdmin['purpose'])); ?></p>
                        <p style="margin-right: 10%;"><?php echo nl2br(htmlspecialchars($HistoryListAdmin['lab'])); ?></p>
                        <small style="margin-right: 11%;"><?php echo $HistoryListAdmin['login']; ?></small>
                        <small style="margin-right: 11%;"><?php echo $HistoryListAdmin['logout']; ?></small>
                        <small><?php echo $HistoryListAdmin['date']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Sit-In.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("signout-btn")) {
            let idNo = event.target.getAttribute("data-id");
            let studentId = event.target.getAttribute("data-student-id");

            if (confirm("Are you sure you want to sign out this user?")) {
                // Step 1: Sign Out the User
                fetch('./components/deleteSitIn.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'idNo=' + encodeURIComponent(idNo)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("User signed out successfully!");

                        // Step 2: Ask to give a point
                        if (confirm("Do you want to give this student 1 point?")) {
                            fetch('./components/givePoint.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: 'student_id=' + encodeURIComponent(studentId)
                            })
                            .then(res => res.json())
                            .then(resData => {
                                if (resData.success) {
                                    alert("1 point awarded to student.");
                                } else {
                                    alert("Failed to award point: " + resData.error);
                                }
                            })
                            .catch(err => {
                                console.error("Error giving point:", err);
                                alert("Something went wrong while giving the point.");
                            });
                        }

                        location.reload(); // Reload after sign out
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => console.error('Sign-out Error:', error));
            }
        }
    });
});

</script>

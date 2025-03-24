<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$SitInList = json_decode(getAllSitIn($conn), true);
?>

<div class="SitInDiv">
    <div class="top">
        <form id="searchForm">
            <input type="text" id="searchInput" name="query" placeholder="Search users...">
            <div id="searchResults"></div>
        </form>
        <button class="sitinNav" id="currentBtn" type="button">Current</button>
        <button class="sitinNav" id="historyBtn" type="button">History</button>

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
                        <button class="signout-btn" data-id="<?php echo $SitInlist['idNo']; ?>">Sign Out</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Sit-In.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Sign Out button functionality
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("signout-btn")) {
            let idNo = event.target.getAttribute("data-id");
            if (confirm("Are you sure you want to sign out this user?")) {
                fetch('./components/deleteSitIn.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'idNo=' + encodeURIComponent(idNo)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("User signed out successfully!");
                        location.reload(); // ✅ Reload the page
                    } else {
                        alert("Error: " + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // History button functionality
        if (event.target.id === "historyBtn") {
            console.log("History button clicked!");
            fetch('./components/sitinHistory.php')
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.bottom').innerHTML = data;
                })
                .catch(error => console.error('Error loading history:', error));
        }

        // Current button functionality
        if (event.target.id === "currentBtn") {
            console.log("Current button clicked!");
            fetch('./components/sitinCurrent.php')
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.bottom').innerHTML = data;
                })
                .catch(error => console.error('Error loading current:', error));
        }
    });

});
</script>

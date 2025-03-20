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
    </div>
    <div class="bottom">
        <?php if (!empty($SitInList)): ?>
            <?php foreach ($SitInList as $SitInlist): ?>
                <div class="announcement-item" id="sitIn-<?php echo $SitInlist['idNo']; ?>">
                    <h3><?php echo htmlspecialchars($SitInlist['idNo']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($SitInlist['student_name'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($SitInlist['purpose'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($SitInlist['lab'])); ?></p>
                    <small><?php echo $SitInlist['SitInTime']; ?></small>
                    <br>
                    <button class="signout-btn" data-id="<?php echo $SitInlist['idNo']; ?>">Sign Out</button>
                    <hr>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No Sit-In.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("click", function(event) {
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
                    location.reload(); // ✅ Reloads the page after successful transaction
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
});
</script>

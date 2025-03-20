<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$userData = getProfile($conn);
$userRole = isset($userData['role']) ? $userData['role'] : 'admin';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idNo = $_POST['idNo'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $middleName = $_POST['middleName'];
  $course = $_POST['course']; 
  $yearLevel = $_POST['yearLevel'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  updateUser($conn, $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username);
}
$conn->close();

if (isset($_GET['logout'])) {
  logout();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/global.css" />
  <link rel="stylesheet" href="./styles/AdminDash.css" />
  <link rel="stylesheet" href="./styles/AdminAnnouncement.css" />
  <title>User Dashboard</title>
  <script src="./components/jsFunctions.js" defer></script>
</head>

<body>
  <div class="mainContainer">
    <div class="UserDashDiv">
      <nav>
        <h1 id="PageTitle">Announcements</h1>
        <ul>
        <li><a href="AdminStatsRep.php" onclick="changeDashboard('AdminStatsRep.php'); return false;">Statistics & Reports</a></li>
          <li><a href="AdminAnnouncements.php" onclick="changeDashboard('AdminAnnouncements.php'); return false;">Announcements</a></li>
          <li><a href="AdminSitIn.php" onclick="changeDashboard('AdminSitIn.php'); return false;">Sit-in</a></li>
          <li><a href="AdminStudentList.php" onclick="changeDashboard('AdminStudentList.php'); return false;">Student List</a></li>
          <li><a href="AdminFeedback.php" onclick="changeDashboard('AdminFeedback.php'); return false;">Feedbacks</a></li>
          <li id="Profile">
            <a href="#!" onclick="toggleDropdown(event)">
              <img src="./assets/profileIcon.jpg" alt="Profile Icon" id="Profile_Icon" />
            </a>
            <ul class="dropdown" id="dropdownMenu">
              <li class="DropdownList"><a href="javascript:void(0)" class="DropdownLabel" onclick="changeDashboard('UserProfile.php')">View Profile</a></li>
              <li class="DropdownList"><a href="UserDash.php?logout=true" class="DropdownLabel" onclick="changeDashboard('UserAnnouncements.php')">Sign Out</a></li>
            </ul>
          </li>
        </ul>
      </nav>
      <div id="content">

      </div>
    </div>
  </div>
</body>

</html>

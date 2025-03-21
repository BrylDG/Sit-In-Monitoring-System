<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$userData = getProfile($conn);
$userRole = isset($userData['role']) ? $userData['role'] : 'student';

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
  <link rel="stylesheet" href="./styles/UserDash.css" />
  <link rel="stylesheet" href="./styles/UserHistory.css" />
  <link rel="stylesheet" href="./styles/UserAnnouncement.css" />
  <title>User Dashboard</title>
  <script src="./components/jsFunctions.js" defer></script>
</head>

<body data-role="<?php echo htmlspecialchars(trim($userRole), ENT_QUOTES, 'UTF-8'); ?>">
  <div class="mainContainer">
    <div class="UserDashDiv">
      <nav>
        <h1 id="PageTitle">Announcements</h1>
        <ul>
          <li><a href="UserAnnouncements.php" onclick="changeDashboard('UserAnnouncements.php'); return false;">Announcements</a></li>
          <li><a href="UserHistory.php" onclick="changeDashboard('UserHistory.php'); return false;">History</a></li>
          <li><a href="UserReservation.php" onclick="changeDashboard('UserReservation.php'); return false;">Reservation</a></li>
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

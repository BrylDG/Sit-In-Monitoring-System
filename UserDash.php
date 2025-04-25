<?php
$conn = new mysqli("localhost", "root", "", "sitinmonitoringsystem");
date_default_timezone_set('Asia/Manila');
$currentTime = date('H:i:s');

// Open labs only at their open time (if they were manually closed)
$conn->query("
    UPDATE labschedules 
    SET status = 'Open', manually_closed = 0 
    WHERE manually_closed = 1 AND TIME('$currentTime') >= open_time AND TIME('$currentTime') < ADDTIME(open_time, '00:01:00')
");

// Close labs that are outside of schedule and not manually reopened
$conn->query("
    UPDATE labschedules 
    SET status = 'Closed' 
    WHERE manually_closed = 0 AND (TIME('$currentTime') < open_time OR TIME('$currentTime') > close_time)
");
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
  <link rel="stylesheet" href="./styles/AdminResources.css" />
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
          <li><a href="UserLabSchedules.php" onclick="changeDashboard('UserLabSchedules.php'); return false;">Lab Schedules</a></li>
          <li><a href="UserReservation.php" onclick="changeDashboard('UserReservation.php'); return false;">Reservation</a></li>
          <li><a href="UserResources.php" onclick="changeDashboard('UserResources.php'); return false;">Recources</a></li>
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

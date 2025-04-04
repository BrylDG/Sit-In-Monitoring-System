<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sitinmonitoringsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
global $userData;
$userData = [];

 function login($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['password'] === $password) {
            $_SESSION['username'] = $username;
            getProfile($conn);
            if ($user['role'] === 'student') {
                header("Location: UserDash.php");
                exit();
            } else {
                header("Location: AdminDash.php");
                exit();
            }

        } else {
            return false;
        }
    } else {
        return false;
    }
    $stmt->close();
 }

 function register($conn, $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username, $password) {
    // ✅ Start transaction to ensure both inserts succeed
    $conn->begin_transaction();

    try {
        // ✅ Insert into `users` table
        $stmt = $conn->prepare("
            INSERT INTO users (idNo, firstName, lastName, middleName, course, yearLevel, email, username, password, profileImage, created_at, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'image.png', DEFAULT, 'student')
        ");
        $stmt->bind_param("sssssssss", $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username, $password);
        $stmt->execute();
        $stmt->close();

        // ✅ Insert into `sessions` table (Default sessions = 30)
        $stmt = $conn->prepare("INSERT INTO sessions (idNo, sessions) VALUES (?, 30)");
        $stmt->bind_param("s", $idNo);
        $stmt->execute();
        $stmt->close();

        // ✅ Commit the transaction
        $conn->commit();

        // ✅ Redirect to login page
        echo "<script>
                alert('Registration successful! Redirecting to Login Page.');
                window.location.href = 'index.php';
              </script>";
        exit(); // Ensure script stops execution after redirect

    } catch (Exception $e) {
        // ❌ Rollback if there’s an error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}


 function getProfile($conn) {
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $userData = $result->fetch_assoc();
    } else {
        echo "User not found!";
    }
    $stmt->close();
    return $userData;
 }

 function updateUser($conn, $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username) {
    $sessionUser = $_SESSION['username'];
    $stmt = $conn->prepare("UPDATE users SET idNo = ?, firstName = ?, lastName = ?, middleName = ?, course = ?, yearLevel = ?, email = ?, username = ? WHERE username = ?");
    $stmt->bind_param("sssssssss", $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username, $sessionUser);
    $_SESSION['username'] = $username;
    
    $stmt->execute();
    $stmt->close();
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

function addAnnouncement($conn, $title, $details) {
    $sql = "INSERT INTO announcements (announcement_title, announcement_details) VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $details);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: AdminDash.php");

    } else {
        $stmt->close();
        return "Error: " . $conn->error;
    }
}
 
function getAllAnnouncements($conn) {
    $sql = "SELECT * FROM announcements ORDER BY created_on DESC";
    $result = $conn->query($sql);

    $announcements = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
    }
    return json_encode($announcements);
}

function getAllSitIn($conn) {
    $sql = "SELECT * FROM SitInTable ORDER BY SitInTime DESC";
    $result = $conn->query($sql);

    $SitInList = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $SitInList[] = $row;
        }
    }
    return json_encode($SitInList);
}

function getAllSitInHistory($conn, $idNo) {
    // Ensure idNo is properly escaped to prevent SQL injection
    $idNo = $conn->real_escape_string($idNo);
    
    // Query with filtering condition
    $sql = "SELECT * FROM SitInHistory WHERE idNo = '$idNo' ORDER BY date DESC, logout DESC, login DESC";
    $result = $conn->query($sql);

    $HistoryList = array();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $HistoryList[] = $row;
        }
    }
    return json_encode($HistoryList);
}

function getAllSitInHistoryAdmin($conn) {
    $sql = "SELECT * FROM SitInHistory ORDER BY date, logout, login DESC";
    $result = $conn->query($sql);

    $HistoryList = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $HistoryList[] = $row;
        }
    }
    return json_encode($HistoryList);
}


function getAllSitInHistoryAdminData($conn, $lab, $purpose) {
    // Base SQL query
    $sql = "SELECT * FROM SitInHistory WHERE 1";

    // Add conditions based on the provided filters
    if ($lab && $lab !== 'All') {
        $sql .= " AND lab = ?";
    }
    if ($purpose && $purpose !== 'All') {
        $sql .= " AND purpose = ?";
    }

    // Order the result
    $sql .= " ORDER BY date, logout, login DESC";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters dynamically
        $types = '';
        $params = [];
        
        if ($lab && $lab !== 'All') {
            $types .= 's';
            $params[] = $lab;
        }
        if ($purpose && $purpose !== 'All') {
            $types .= 's';
            $params[] = $purpose;
        }

        // Bind the parameters
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the results
        $HistoryList = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $HistoryList[] = $row;
            }
        }

        // Return results as JSON
        return json_encode($HistoryList);
    } else {
        return json_encode(["error" => "Failed to prepare the statement"]);
    }
}



    function getAllSitInDailyHistoryAdmin($conn) {
        $sql = "SELECT * FROM SitInHistory WHERE date = CURRENT_DATE ORDER BY date, logout, login DESC";
        $result = $conn->query($sql);

        $HistoryList = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $HistoryList[] = $row;
            }
        }
        return json_encode($HistoryList);
    }

    function fetchStudents($conn) {
        $sql = "SELECT *, sessions 
                FROM users 
                LEFT JOIN sessions ON users.idNo = sessions.idNo 
                WHERE users.role = 'student' 
                GROUP BY users.idNo";
    
        $result = $conn->query($sql);
    
        // Display inside the studentContainer div
        echo '<div id="studentContainer">';
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="studentCard">';
                echo 'ID No: ' . htmlspecialchars($row['idNo']) . '<br>';
                echo 'First Name: ' . htmlspecialchars($row['firstName']) . '<br>';
                echo 'Middle Name: ' . htmlspecialchars($row['middleName']) . '<br>';
                echo 'Last Name: ' . htmlspecialchars($row['lastName']) . '<br>';
                echo 'Email: ' . htmlspecialchars($row['email']) . '<br>';
                echo 'Sessions: ' . htmlspecialchars($row['sessions']) . '<br>';
                echo '<button class="reset-btn" value="' . htmlspecialchars($row['idNo']) . '">Reset Sessions</button>';
                echo '</div>';
                echo '<button class="reset-btn" value="All">Reset All Sessions</button>';
            }
        } else {
            echo 'No students found.';
        }
    
        echo '</div>';
    
        // Close connection
        $conn->close();
    }
    

function countPurpose($conn) {
    $languages = ['Java', 'C', 'C#', 'C++', 'Python', 'PHP', 'ASP.NET'];
    $counts = [];

    foreach ($languages as $language) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM sitinhistory WHERE purpose = ?");
        $stmt->bind_param("s", $language);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        // Store in associative array
        $counts[$language] = $count ?? 0;
    }

    return $counts;
}

function generatePurpose($conn, $languageFilter) {
    $languages = ['Java', 'C', 'C#', 'C++', 'Python', 'PHP', 'ASP.NET'];
    $counts = [];

    if ($languageFilter === "All") {
        foreach ($languages as $language) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM sitinhistory WHERE purpose = ?");
            $stmt->bind_param("s", $language);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            $counts[$language] = $count ?? 0;
        }
    } else {
        // Check if the provided filter exists in the list
        if (in_array($languageFilter, $languages)) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM sitinhistory WHERE purpose = ?");
            $stmt->bind_param("s", $languageFilter);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            $counts[$languageFilter] = $count ?? 0;
        } else {
            // Invalid filter, return zero for all
            foreach ($languages as $language) {
                $counts[$language] = 0;
            }
        }
    }

    return $counts;
}


function countLab($conn) {
    $languages = ['524', '526', '530'];
    $counts = [];

    foreach ($languages as $language) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM sitinhistory WHERE lab = ?");
        $stmt->bind_param("s", $language);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        // Store in associative array
        $counts[$language] = $count ?? 0;
    }

    return $counts;
}

function generateLab($conn, $labFilter) {
    $labs = ['524', '526', '530'];
    $counts = [];

    if ($labFilter === "All") {
        foreach ($labs as $lab) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM sitinhistory WHERE lab = ?");
            $stmt->bind_param("s", $lab);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            $counts[$lab] = $count ?? 0;
        }
    } else {
        // Check if the provided filter exists in the list
        if (in_array($labFilter, $labs)) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM sitinhistory WHERE lab = ?");
            $stmt->bind_param("s", $labFilter);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            $counts[$labFilter] = $count ?? 0;
        } else {
            // Invalid filter, return zero for all
            foreach ($labs as $lab) {
                $counts[$lab] = 0;
            }
        }
    }

    return $counts;
}


function countByDayOfWeek($conn) {
    $daysOfWeek = [
        'Sunday' => 0,
        'Monday' => 0,
        'Tuesday' => 0,
        'Wednesday' => 0,
        'Thursday' => 0,
        'Friday' => 0,
        'Saturday' => 0
    ];

    $stmt = $conn->prepare("SELECT DAYNAME(date) AS day, COUNT(*) AS count FROM sitinhistory GROUP BY day");
    $stmt->execute();
    $stmt->bind_result($day, $count);
    
    while ($stmt->fetch()) {
        if (isset($daysOfWeek[$day])) {
            $daysOfWeek[$day] = $count;
        }
    }
    
    $stmt->close();

    return $daysOfWeek;
}

function resetSessions ($conn, $resetType) {
    if ($resetType === "All") {
        $stmt = $conn->prepare("UPDATE sessions SET sessions = 30");
    }
    else {
        $stmt = $conn->prepare("UPDATE sessions SET sessions = 30 WHERE idNo = $resetType");
    }
    $stmt->execute();
}

?>


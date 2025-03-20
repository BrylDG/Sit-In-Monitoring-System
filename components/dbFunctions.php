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
    $stmt = $conn->prepare("INSERT INTO users (idNo, firstName, lastName, middleName, course, yearLevel, email, username, password, profileImage, created_at, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'image.png', DEFAULT, 'student')");
        $stmt->bind_param("sssssssss", $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username, $password);

        if ($stmt->execute()) {
            echo "Registration successful! Redirecting to Login Page.";
            header("Location: index.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
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

?>

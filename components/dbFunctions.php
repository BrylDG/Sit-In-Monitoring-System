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
            header("Location: UserDash.php");
            exit();
        } else {
            return false;
        }
    } else {
        return false;
    }
    $stmt->close();
 }

 function register($conn, $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username, $password) {
    $stmt = $conn->prepare("INSERT INTO users (idNo, firstName, lastName, middleName, course, yearLevel, email, username, password, profileImage, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'image.png', DEFAULT)");
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

 function updateUser($conn) {
    $stmt = $conn->prepare("UPDATE users SET em");
 }
 
?>

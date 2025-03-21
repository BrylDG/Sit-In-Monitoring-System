<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="stylesheet" href="./styles/global.css">
    <title>Login</title>
</head>

<body>
    <div class="mainContainer">
        <div class="LoginForm">
            <div id="top">
                <div id="topinside">
                    <img src="./assets/CCS_LOGO.png" alt="loginlogo" id="loginlogo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form method="POST" action="">
                <label>Username:</label>
                <input type="text" name="username" id="username" required>
                <label>Password:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Login</button>
            </form>
            <label id="register">Don't have an account?
                <a href="register.php">Register here.</a>
            </label>
        </div>
    </div>

    <?php
    session_start();
    require './components/dbFunctions.php';
    $conn = new mysqli($servername, $username, $password, $database);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        login($conn, $username, $password);
    }
    $conn->close();
    ?>
</body>

</html>
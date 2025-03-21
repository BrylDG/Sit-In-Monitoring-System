<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/register.css">
    <link rel="stylesheet" href="./styles/global.css">
    <title>Register</title>
</head>

<body>
    <div class="mainContainer">
        <div id="form-container">
            <div id="top">
                <div id="topinside">
                    <img src="./assets/CCS_LOGO.png" alt="logo" id="logo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form method="POST" action="register.php">
                <div id="FirstDivision">
                    <div class="division">
                        <label for="idNo">ID Number</label>
                        <input type="text" name="idNo" required />
                    </div>
                    <div class="division">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstName" required />
                    </div>
                </div>
                <div id="SecondDivision">
                    <div class="division">
                        <label for="lastName">Last Name</label>
                        <input type="text" name="lastName" required />
                    </div>
                    <div class="division">
                        <label for="middleName">Middle Name</label>
                        <input type="text" name="middleName" />
                    </div>
                </div>
                <div id="ThirdDivision">
                    <div class="division">
                        <label for="course">Course</label>
                        <select name="course" required>
                            <option value="">Select Course</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                        </select>
                    </div>
                    <div class="division">
                        <label for="yearLevel">Year Level</label>
                        <select name="yearLevel" required>
                            <option value="">Select Year Level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
                <div class="division">
                    <label for="email">Email</label>
                    <input type="email" name="email" required />
                </div>
                <div class="division">
                    <label for="username">Username</label>
                    <input type="text" name="username" required />
                </div>
                <div id="FourthDivision">
                    <div class="division">
                        <label for="password">Password</label>
                        <input type="password" name="password" required />
                    </div>
                    <div class="division">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" name="confirmPassword" required />
                    </div>
                </div>
                <button type="submit" onClick="showAlert()">Register</button>
            </form>
            <label id="login">Already have an account?
                <a href="index.php">Login here.</a>
            </label>
        </div>
    </div>

    <?php
        require './components/dbFunctions.php';
        $conn = new mysqli($servername, $username, $password, $database);
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idNo = trim($_POST['idNo']);
            $firstName = trim($_POST['firstName']);
            $lastName = trim($_POST['lastName']);
            $middleName = trim($_POST['middleName']);
            $course = trim($_POST['course']);
            $yearLevel = trim($_POST['yearLevel']);
            $email = trim($_POST['email']);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirmPassword']);
        
            if ($password !== $confirmPassword) {
                echo "Passwords do not match.";
            } else {
                register($conn, $idNo, $firstName, $lastName, $middleName, $course, $yearLevel, $email, $username, $password);
            }
        }

        $conn->close();
    ?>
</body>

</html>

<?php
require './components/dbFunctions.php';
$conn = new mysqli($servername, $username, $password, $database);
$userData = getProfile($conn);
?>

<div class="ProfilePage">
    <img src="./assets/profileIcon.jpg" alt="Profile Icon" id="Profile_Pic" />
            <div class="formContainer">
                <div class="section">
                    <div class="container">
                        <label>ID Number:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['idNo']); ?>" readOnly />
                    </div>
                    <div class="container">
                        <label>Username:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['username']); ?>" readOnly />
                    </div>
                </div>

                <div class="section">
                    <div class="container">
                        <label>First Name:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['firstName']); ?>" readOnly />
                    </div>
                    <div class="container">
                        <label>Last Name:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['lastName']); ?>" readOnly />
                    </div>
                    <div class="container">
                        <label>Middle Name:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['middleName']); ?>" readOnly />
                    </div>
                </div>

                <div class="section">
                    <div class="container">
                        <label>Course:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['course']); ?>" readOnly />
                    </div>
                    <div class="container">
                        <label>Year Level:</label>
                        <input class="profileInput" type="text" value="<?php echo htmlspecialchars($userData['yearLevel']); ?>" readOnly />
                    </div>
                </div>

                <div class="section" id="emailSection">
                    <div class="container" id="emailContainer">
                        <label>Email:</label>
                        <input class="profileInput" type="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readOnly />
                    </div>
                </div>
                <button id="editButton" onclick="toggleEditProfile()">Edit</button>
                <button id="updateButton" onclick="toggleEditProfile()" style="display:none;">Update</button>
            </div>
</div>
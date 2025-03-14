import '../styles/ProfilePage.css'
import default_icon from "../assets/profileIcon.jpg";

function ProfilePage() {
    return (
        <div className="ProfilePage">
            <img src={default_icon} alt="Profile Icon" id="Profile_Pic" />
            <div className="formContainer">
                <div className="section">
                    <div className="container">
                        <label htmlFor="idNo">ID Number:</label>
                        <input className="profileInput" type="text" id="idNo" value="<?php echo htmlspecialchars($user['idNo']); ?>" readOnly />
                    </div>
                    <div className="container">
                        <label htmlFor="username">Username:</label>
                        <input className="profileInput" type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readOnly />
                    </div>
                </div>

                <div className="section">
                    <div className="container">
                        <label htmlFor="firstName">First Name:</label>
                        <input className="profileInput" type="text" id="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" readOnly />
                    </div>
                    <div className="container">
                        <label htmlFor="lastName">Last Name:</label>
                        <input className="profileInput" type="text" id="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" readOnly />
                    </div>
                    <div className="container">
                        <label htmlFor="middleName">Middle Name:</label>
                        <input className="profileInput" type="text" id="middleName" value="<?php echo htmlspecialchars($user['middleName']); ?>" readOnly />
                    </div>
                </div>

                <div className="section">
                    <div className="container">
                        <label htmlFor="course">Course:</label>
                        <input className="profileInput" type="text" id="course" value="<?php echo htmlspecialchars($user['course']); ?>" readOnly />
                    </div>
                    
                    <div className="container">
                        <label htmlFor="yearLevel">Year Level: </label>
                        <input className="profileInput" type="text" id="yearLevel" value="<?php echo htmlspecialchars($user['yearLevel']); ?>" readOnly />
                    </div>
                </div>
                <div className="section" id="emailSection">
                    <div className="container" id="emailContainer">
                        <label htmlFor="email">Email:</label>
                        <input className="profileInput" type="email" id="email" value="<?php echo htmlspecialchars($user['emailAddress']); ?>" readOnly />
                    </div> 
                </div>
                <button id="editButton">Edit</button>
            </div>

        </div>
    );
}

export default ProfilePage;

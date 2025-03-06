import '../styles/RegisterForm.css';
import css_logo from '../assets/CCS_LOGO.png';
import { Link } from 'react-router-dom';

function RegisterForm() {
    return (
        <div id="form-container">
            <div id="top">
                <div id="topinside">
                    <img src={css_logo} alt="logo" id="logo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form action="" method="POST">
                <div id="FirstDivision">
                    <div className="division">
                        <label htmlFor="idNo">ID Number</label>
                        <input type="number" name="idNo" required />
                    </div>
                    <div className="division">
                        <label htmlFor="firstName">First Name</label>
                        <input type="text" name="firstName" required />
                    </div>
                </div>
                <div id="SecondDivision">
                    <div className="division">
                        <label htmlFor="lastName">Last Name</label>
                        <input type="text" name="lastName" required />
                    </div>
                    <div className="division">
                        <label htmlFor="middleName">Middle Name</label>
                        <input type="text" name="middleName" />
                    </div>
                </div>
                <div id="ThirdDivision">
                    <div className="division">
                        <label htmlFor="course">Course</label>
                        <select name="course" id="course" className="custom-dropdown">
                            <option value="" disabled selected style={{ display: 'none' }}>Select Course</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                            <option value="BEEd">BEEd</option>
                            <option value="BSEd">BSEd</option>
                            <option value="BSBA">BSBA</option>
                            <option value="BSME">BSME</option>
                        </select>
                    </div>
                    <div className="division">
                        <label htmlFor="yearLevel">Year Level</label>
                        <select name="yearLevel" id="yearLevel" className="custom-dropdown">
                            <option value="" disabled selected style={{ display: 'none' }}>Select Year Level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div className="division">
                        <label htmlFor="email">Email</label>
                        <input type="email" name="email" required style={{ width: '93%' }} />
                    </div>
                    <div className="division">
                        <label htmlFor="username">Username</label>
                        <input type="text" name="username" required style={{ width: '93%' }} />
                    </div>
                </div>
                <div id="FourthDivision">
                    <div className="division">
                        <label htmlFor="password">Password</label>
                        <input type="password" name="password" required />
                    </div>
                    <div className="division">
                        <label htmlFor="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" required />
                    </div>
                </div>
                <button type="submit">Register</button>
            </form>
            <label id="login">Already have an account? <Link to="/">Login here.</Link></label>
        </div>
    );
}

export default RegisterForm;
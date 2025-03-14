import '../styles/RegisterForm.css';
import css_logo from '../assets/CCS_LOGO.png';
import { Link, useNavigate } from 'react-router-dom';
import { useState } from 'react';

function RegisterForm() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        idNo: '',
        firstName: '',
        lastName: '',
        middleName: '',
        course: '',
        yearLevel: '',
        emailAddress: '',
        username: '',
        password: '',
        confirmPassword: ''
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        console.log(formData)

        if (formData.password !== formData.confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        try {
            const response = await fetch('http://localhost:5000/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });
            console.log(response)
            const data = await response.json();

            if (data.success) {
                alert("Registration successful! Redirecting to login...");
                navigate('/');
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred during registration.");
        }
    };

    return (
        <div id="form-container">
            <div id="top">
                <div id="topinside">
                    <img src={css_logo} alt="logo" id="logo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form onSubmit={handleSubmit}>
                <div id="FirstDivision">
                    <div className="division">
                        <label htmlFor="idNo">ID Number</label>
                        <input type="number" name="idNo" required onChange={handleChange} />
                    </div>
                    <div className="division">
                        <label htmlFor="firstName">First Name</label>
                        <input type="text" name="firstName" required onChange={handleChange} />
                    </div>
                </div>
                <div id="SecondDivision">
                    <div className="division">
                        <label htmlFor="lastName">Last Name</label>
                        <input type="text" name="lastName" required onChange={handleChange} />
                    </div>
                    <div className="division">
                        <label htmlFor="middleName">Middle Name</label>
                        <input type="text" name="middleName" onChange={handleChange} />
                    </div>
                </div>
                <div id="ThirdDivision">
                    <div className="division">
                        <label htmlFor="course">Course</label>
                        <select name="course" required onChange={handleChange}>
                            <option value="">Select Course</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSCS">BSCS</option>
                        </select>
                    </div>
                    <div className="division">
                        <label htmlFor="yearLevel">Year Level</label>
                        <select name="yearLevel" required onChange={handleChange}>
                            <option value="">Select Year Level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
                <div className="division">
                    <label htmlFor="email">Email</label>
                    <input type="email" name="emailAddress" required onChange={handleChange} />
                </div>
                <div className="division">
                    <label htmlFor="username">Username</label>
                    <input type="text" name="username" required onChange={handleChange} />
                </div>
                <div id="FourthDivision">
                    <div className="division">
                        <label htmlFor="password">Password</label>
                        <input type="password" name="password" required onChange={handleChange} />
                    </div>
                    <div className="division">
                        <label htmlFor="confirmPassword">Confirm Password</label>
                        <input type="password" name="confirmPassword" required onChange={handleChange} />
                    </div>
                </div>
                <button type="submit">Register</button>
            </form>
            <label id="login">Already have an account? <Link to="/">Login here.</Link></label>
        </div>
    );
}

export default RegisterForm;

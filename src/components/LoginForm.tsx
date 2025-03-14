import '../styles/LoginForm.css';
import ccs_logo from '../assets/CCS_LOGO.png';
import { Link, useNavigate } from 'react-router-dom';
import { useState } from 'react';

function LoginForm() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const navigate = useNavigate();

    const handleLogin = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        try {
            const response = await fetch('http://localhost:5000/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            const data = await response.json();

            if (data.success) {
                console.log("Credentials correct, navigating to /dashboard...");
                navigate('/Dashboard');
            } else {
                alert('Invalid username or password');
            }
        } catch (error) {
            console.error("Error during login:", error);
            alert('An error occurred while logging in. Please try again.');
        }
    };

    return (
        <div className="LoginForm">
            <div id="top">
                <div id="topinside">
                    <img src={ccs_logo} alt="loginlogo" id="loginlogo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form onSubmit={handleLogin} method="post">
                <label htmlFor="username">Username:</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                    required
                />
                <label htmlFor="password">Password:</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                />
                <button type="submit">Login</button>
            </form>
            <label id="register">Don't have an account? <Link to="/register">Register here.</Link></label>
        </div>
    );
}

export default LoginForm;

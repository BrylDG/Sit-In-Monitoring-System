import '../styles/LoginForm.css';
import ccs_logo from '../assets/CCS_LOGO.png';
import { Link, useNavigate } from 'react-router-dom';
import { useState } from 'react';

function LoginForm() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const navigate = useNavigate();

    const handleLogin = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault(); // Prevent form from reloading
        console.log("Login button clicked"); // Debugging
        console.log("Username:", username);
        console.log("Password:", password);

        if (username === 'admin' && password === 'admin') {
            console.log("Credentials correct, navigating to /dashboard...");
            navigate('/Dashboard');
        } else {
            console.error("Invalid credentials");
            alert('Invalid username or password');
        }
    };

    return (
        <div className="LoginForm">
            <div id="top">
                <div id="topinside">
                    <img src={ccs_logo} alt="logo" id="logo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form onSubmit={handleLogin} method="post"> {/* Prevents GET params in URL */}
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
import '../styles/LoginForm.css'
import ccs_logo from '../assets/CCS_LOGO.png'

function LoginForm() {
    return (
        <div className="LoginForm">
            <div id="top">
                <div id="topinside">
                    <img src={ccs_logo} alt="logo" id="logo" />
                    <h2>CSS Sit-In Monitoring System</h2>
                </div>
                <hr />
            </div>
            <form>
                <label>Username:</label>
                <input type="text" name="username" id="username" required />
                <label>Password:</label>
                <input type="password" name="password" id="password" required />
                <button type="submit">Login</button>
            </form>

            <label id="register">Don't have an account? <a href="register.php">Register here.</a></label>
        </div>
    )
}

export default LoginForm
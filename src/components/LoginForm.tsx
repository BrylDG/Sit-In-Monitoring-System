import '../styles/LoginForm.css'

function LoginForm() {
    return (
        <div className="LoginForm">
            <form>
                <label>Username:</label>
                <input type="text" name="username" />
                <label>Password:</label>
                <input type="password" name="password" />
                <button type="submit">Login</button>
            </form>
        </div>
    )
}

export default LoginForm
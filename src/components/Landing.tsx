import '../styles/Landing.css'
import { Routes, Route } from "react-router-dom";
import LoginForm from './LoginForm'
import RegisterForm from "./RegisterForm";
import UserDash from './UserDash'

function Landing() {

    return (
        <div className="indexBody">
            <Routes>
                <Route path="/" element={<LoginForm />} />
                <Route path="/register" element={<RegisterForm />} />
                <Route path="/Dashboard/*" element={<UserDash />} />
            </Routes>
        </div>
    )
}

export default Landing


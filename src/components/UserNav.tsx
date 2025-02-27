import default_icon from '../assets/profileIcon.jpg'
import '../styles/UserNav.css'

function UserNav() {
    return (
        <nav>
            <h1>Announcements</h1>
            <ul>
                <li><a href="/settings">Announcements</a></li>
                <li><a href="/logout">History</a></li>
                <li><a href="/reservation">Reservation</a></li>
                <li id="Pofile"><a href="/profile"><img src={default_icon} alt="Profile_Icon" id="Profile_Icon" /></a></li>
            </ul>
        </nav>
    )
}
export default UserNav
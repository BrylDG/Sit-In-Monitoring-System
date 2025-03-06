import UserNav from './UserNav'
import UserAnnouncements from './UserAnnouncements'
import ProfilePage from './ProfilePage'

import '../styles/UserDash.css'

function UserDash() {
    return (
        <div className="UserDashDiv">
            <UserNav />
            <ProfilePage />
        </div>
    )
}

export default UserDash
import UserNav from './UserNav';
import { Routes, Route, Navigate } from "react-router-dom";
import UserAnnouncements from './UserAnnouncements';
import UserHistory from './UserHistory';
import UserReservation from './UserReservation';
import ProfilePage from './ProfilePage';

import '../styles/UserDash.css';

function UserDash() {
    return (
        <div className="UserDashDiv">
            <UserNav />
            <Routes>
                <Route path="Announcements" element={<UserAnnouncements />} />
                <Route index element={<Navigate to="Announcements" replace />} /> {/* Redirect /Dashboard to /Dashboard/Announcements */}
                <Route path="History" element={<UserHistory />} />
                <Route path="Reservation" element={<UserReservation />} />
                <Route path="Profile" element={<ProfilePage />} />
            </Routes>
        </div>
    );
}

export default UserDash;

import { useRef, useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import default_icon from "../assets/profileIcon.jpg";
import "../styles/UserNav.css";

function UserNav() {
    const imgRef = useRef<HTMLImageElement>(null);
    const dropdownRef = useRef<HTMLUListElement>(null);
    const location = useLocation();
    const [dropdownOpen, setDropdownOpen] = useState(false);

    // Extract the active tab from the pathname
    const activeTab = location.pathname.replace("/Dashboard/", "") || "Announcements";

    useEffect(() => {
        const handleClick = (event: MouseEvent) => {
            if (imgRef.current && imgRef.current.contains(event.target as Node)) {
                setDropdownOpen((prev) => !prev);
                return;
            }
            if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
                setDropdownOpen(false);
            }
        };

        document.addEventListener("click", handleClick);
        return () => {
            document.removeEventListener("click", handleClick);
        };
    }, []);

    return (
        <nav>
            {/* Dynamic Page Title */}
            <h1>{activeTab.charAt(0).toUpperCase() + activeTab.slice(1)}</h1>

            <ul>
                {["Announcements", "History", "Reservation"].map((tab) => (
                    <li key={tab}>
                        <Link
                            id="link"
                            to={`/Dashboard/${tab}`}
                            className={activeTab === tab ? "active" : ""}
                        >
                            {tab.charAt(0).toUpperCase() + tab.slice(1)}
                        </Link>
                    </li>
                ))}
                <li id="Profile">
                    <a href="#!">
                        <img ref={imgRef} src={default_icon} alt="Profile Icon" id="Profile_Icon" />
                    </a>
                    {dropdownOpen && (
                        <ul ref={dropdownRef} className="dropdown">
                            <li className="DropdownList"><Link to="/Dashboard/Profile" className="DropdownLabel">View Profile</Link></li>
                            <li className="DropdownList"><Link to="/" className="DropdownLabel">Sign Out</Link></li>
                        </ul>
                    )}
                </li>
            </ul>
        </nav>
    );
}

export default UserNav;

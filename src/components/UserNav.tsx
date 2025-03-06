import { useRef, useEffect, useState } from "react";
import { useActiveTab } from "../components/backendFunctions"; // Import from backendFunctions.ts
import default_icon from "../assets/profileIcon.jpg";
import "../styles/UserNav.css";

function UserNav() {
    const imgRef = useRef<HTMLImageElement>(null);
    const dropdownRef = useRef<HTMLUListElement>(null);
    const { activeTab, changeTab } = useActiveTab(); // Get active tab from URL
    const [dropdownOpen, setDropdownOpen] = useState(false);

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

    const handleTabClick = (event: React.MouseEvent, tabName: string) => {
        event.preventDefault();
        changeTab(tabName);
    };

    return (
        <nav>
            {/* Dynamic Page Title */}
            <h1>{activeTab.charAt(0).toUpperCase() + activeTab.slice(1)}</h1>

            <ul>
                {["Announcements", "History", "Reservation"].map((tab) => (
                    <li key={tab}>
                        <a 
                            href={`/${tab}`} 
                            className={activeTab === tab ? "active" : ""}
                            onClick={(e) => handleTabClick(e, tab)}
                        >
                            {tab.charAt(0).toUpperCase() + tab.slice(1)}
                        </a>
                    </li>
                ))}
                <li id="Profile">
                    <a href="#!">
                        <img ref={imgRef} src={default_icon} alt="Profile Icon" id="Profile_Icon" />
                    </a>
                    {dropdownOpen && (
                        <ul ref={dropdownRef} className="dropdown">
                            <li className="DropdownList"><a href="/Profile Page" className="DropdownLabel">View Profile</a></li>
                            <li className="DropdownList"><a href="/logout" className="DropdownLabel">Sign Out</a></li>
                        </ul>
                    )}
                </li>
            </ul>
        </nav>
    );
}

export default UserNav;

import { useState } from "react";

export function useActiveTab() {
    // Extract tab name from the current URL path
    const getTabFromURL = () => {
        const path = window.location.pathname.split("/")[1]; // Get first path segment
        return path || "announcements"; // Default to 'announcements' if path is empty
    };

    const [activeTab, setActiveTab] = useState(getTabFromURL());

    const changeTab = (tabName: string) => {
        setActiveTab(tabName);
    };

    return { activeTab, changeTab };
}

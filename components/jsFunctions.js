// NAVIGATION SCRIPT
function changeDashboard(page) {
    fetch(page)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
            setActiveLink(page);
            sessionStorage.setItem('currentDashboard', page);

            // Get user role from PHP (ensure trimming to remove spaces)
            const userRole = document.body.getAttribute("data-role").toLowerCase();
            console.log("User Role:", userRole); // ✅ Now correctly logs role


            // Define separate page titles for students and admins
            const studentPages = {
                "UserAnnouncements.php": "Announcements",
                "UserHistory.php": "History",
                "UserReservation.php": "Reservation",
                "UserResources.php": "Resources",
                "UserLabSchedules.php": "Lab Schedules",
                "UserProfile.php": "Profile"
            };

            const adminPages = {
                "AdminAnnouncements.php": "Announcements",
                "AdminFeedback.php": "Feedbacks",
                "AdminSitIn.php": "Sit-in",
                "AdminStatsRep.php": "Statistics & Reports",
                "AdminStudentList.php": "Student List",
                "AdminResources.php": "Resources",
                "AdminLabSchedules.php": "Lab Schedules",
                "UserProfile.php": "Profile",
                "Leaderboard.php": "Leaderboard"
            };

            const pageTitles = (userRole === "student") ? studentPages : adminPages;
            const pageFile = page.split('/').pop();

            const pageTitleElement = document.getElementById('PageTitle');
            if (pageTitleElement && pageTitles[pageFile]) {
                pageTitleElement.textContent = pageTitles[pageFile];
            } else {
                console.warn("Page title not found for:", pageFile);
            }
        })
        .catch(error => console.error("Error loading component:", error));
}

// ROLE-BASED NAVIGATION
window.onload = function () {
    const userRole = document.body.getAttribute("data-role");
    console.log("User Role:", userRole);

    const defaultPage = (userRole === "admin") ? "AdminStatsRep.php" : "UserAnnouncements.php";
    const lastPage = sessionStorage.getItem('currentDashboard') || defaultPage;

    changeDashboard(lastPage);
    setActiveLink(lastPage);
};

// SET ACTIVE LINK IN NAVIGATION
function setActiveLink(page) {
    const links = document.querySelectorAll('nav a');

    links.forEach(link => {
        const linkHref = link.getAttribute('href').split('/').pop();
        const pageFile = page.split('/').pop();

        if (linkHref === pageFile) {
            if (pageFile === "AdminStatsRep.php") {
                createPurposeChart();
                createLabChart();
                createDayChart();
            }
            else if (pageFile === "AdminResources.php" || pageFile === "UserResources.php") {
                listFiles();
            }
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

//----------------------------- DROPDOWN MENU SCRIPT -------------------------//
window.addEventListener('click', function (event) {
    const dropdown = document.getElementById('dropdownMenu');
    const profile = document.getElementById('Profile');
    if (!profile.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');
}

/////////////////////////////////////////////////////////////////////////


//------------------------- ANNOUNCEMENT SCRIPTS ----------------------//

document.addEventListener("click", function (event) {
    if (event.target.id === "addAnnouncement") {
        console.log("Add Announcement button clicked!"); // Debugging
        showAnnouncementForm();
    }
});

function showAnnouncementForm() {
    console.log("Showing announcement form..."); // Debugging
    const overlay = document.createElement("div");
    overlay.id = "announcementOverlay";
    overlay.classList.add("overlay");

    const form = document.createElement("form");
    form.classList.add("announcement-form");
    form.method = "POST";
    form.action = "AdminAnnouncements.php";

    form.innerHTML = `
        <h2>Add Announcement</h2>
        <label for="announcementTitle">Title:</label>
        <input type="text" name="announcementTitle" id="announcementTitle" placeholder="Enter title" required>
        
        <label for="announcementContent">Content:</label>
        <textarea name="announcementContent" id="announcementContent" placeholder="Enter announcement details" required></textarea>

        <div class="form-buttons">
            <button type="submit" name="submitAnnouncement" id="submitAnnouncement">Submit</button>
            <button type="button" id="cancelAnnouncement">Cancel</button>
        </div>
    `;

    overlay.appendChild(form);
    document.body.appendChild(overlay);

    document.getElementById("cancelAnnouncement").addEventListener("click", function () {
        document.body.removeChild(overlay);
    });

    overlay.addEventListener("click", function (event) {
        if (event.target === overlay) {
            document.body.removeChild(overlay);
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const addAnnouncementBtn = document.getElementById("addAnnouncement");
    if (addAnnouncementBtn) {
        addAnnouncementBtn.addEventListener("click", showAnnouncementForm);
    }
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function toggleEditProfile() {
    const inputs = document.querySelectorAll('.formContainer input');
    const editBtn = document.getElementById('editButton');
    const updateBtn = document.getElementById('updateButton');

    if (editBtn.style.display !== 'none') {
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.classList.add('editable');
        });
        editBtn.style.display = 'none';
        updateBtn.style.display = 'inline-block';
    } else {
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.remove('editable');
        });
        updateBtn.style.display = 'none';
        editBtn.style.display = 'inline-block';
    }
}


//-------------------------- SIT-IN SCRIPTS ---------------------------//

// ✅ Ensure the search function initializes after DOM loads
document.addEventListener("DOMContentLoaded", function () {
    initializeSearch();
});

// ✅ Attach event listener to dynamically created buttons
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("update-btn")) {
        submitSitInForm(event);
    }
});

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') { // ✅ Trigger search when Enter is pressed
                event.preventDefault(); // Prevent form submission or page refresh

                let query = this.value.trim();
                if (query.length > 0) {
                    fetch('./components/searchUsers.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'query=' + encodeURIComponent(query)
                    })
                        .then(response => response.text()) // ✅ Get raw text first
                        .then(text => {
                            console.log("Raw Response:", text); // ✅ Debugging
                            try {
                                return JSON.parse(text); // ✅ Parse JSON
                            } catch (error) {
                                console.error("Error parsing JSON:", error, "\nResponse was:", text);
                                return []; // Return empty array to prevent UI crashes
                            }
                        })
                        .then(data => {
                            if (data.length > 0) {
                                showSearchOverlay(data);
                            } else {
                                alert("No results found.");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            }
        });
    } else {
        console.log("Waiting for searchInput to load...");
        setTimeout(initializeSearch, 500);
    }
}

// ✅ Function to create and show overlay modal
function showSearchOverlay(users) {
    // Remove existing overlay if any
    let existingOverlay = document.getElementById('searchOverlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }

    // Create overlay container
    let overlay = document.createElement('div');
    overlay.setAttribute('id', 'searchOverlay');
    overlay.classList.add('overlay');

    // Create overlay content
    let overlayContent = document.createElement('div');
    overlayContent.classList.add('overlay-content');

    // Close button
    let closeButton = document.createElement('button');
    closeButton.textContent = "Close";
    closeButton.classList.add('close-btn');
    closeButton.addEventListener('click', function () {
        overlay.remove();
    });

    // Create form inside overlay
    let form = document.createElement('form');
    form.setAttribute('id', 'userSearchForm');
    form.setAttribute('method', 'POST');

    users.forEach(user => {
        let userDiv = document.createElement('div');
        userDiv.classList.add('user-card');

        userDiv.innerHTML = `
            <div class="user-info">
                <img src="${user.profileImage ? user.profileImage : 'default-profile.png'}" alt="Profile Image" class="profile-img">
                
                <label>ID Number:</label>
                <input type="text" name="idNo" value="${user.idNo}" readonly>

                <label>First Name:</label>
                <input type="text" name="firstName" value="${user.firstName}" readonly>

                <label>Middle Name:</label>
                <input type="text" name="middleName" value="${user.middleName ? user.middleName : ''}" readonly>

                <label>Last Name:</label>
                <input type="text" name="lastName" value="${user.lastName}" readonly>

                <label>Course:</label>
                <input type="text" name="course" value="${user.course}" readonly>

                <label>Year Level:</label>
                <input type="text" name="yearLevel" value="${user.yearLevel}" readonly>

                <label>Email:</label>
                <input type="email" name="email" value="${user.email}" readonly>

                <label>Purpose:</label>
                <input type="text" name="purpose">

                <label>Lab:</label>
                <input type="text" name="lab">
                
                <button type="submit" class="update-btn">Sit-in</button>
            </div>
        `;

        form.appendChild(userDiv);
    });

    // Append elements to overlay
    overlayContent.appendChild(closeButton);
    overlayContent.appendChild(form);
    overlay.appendChild(overlayContent);
    document.body.appendChild(overlay);
}

function submitSitInForm(event) {
    event.preventDefault(); // ✅ Prevent form refresh

    let form = event.target.closest('form');
    if (!form) {
        console.error("No form found!");
        return;
    }

    let formData = new FormData(form);

    fetch('./components/updateSitInTable.php', { // ✅ Ensure correct path
        method: 'POST',
        body: formData
    })
        .then(response => response.text()) // ✅ Get raw text first
        .then(text => {
            console.log("Raw Response:", text); // ✅ Debugging
            try {
                return JSON.parse(text); // ✅ Now parse JSON
            } catch (error) {
                console.error("Error parsing JSON:", error, "\nResponse was:", text);
                return { error: "Invalid JSON response" }; // ✅ Prevent UI crash
            }
        })
        .then(data => {
            if (data.success) {
                alert("Sit-in record added successfully!");
                document.getElementById('searchOverlay').remove(); // ✅ Close overlay
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

////////////////////////////////////////////////////////////////////////////////////////////////////

//------------------------------ DOWNLOAD FILE / REPORTS SCRIPTS -------------------------------//

// Download Button Listener
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("download")) {
        console.log("Download Reports button clicked!"); // Debugging
        showExportForm(event);
    }
});

// Show Export Form Function
function showExportForm(event) {
    let overlay = document.createElement("div");
    overlay.classList.add("overlay");

    // Determine the source of the call (button id)
    const source = event.target.closest('[data-type]')?.dataset.type || 'reports';
    console.log(source);

    overlay.innerHTML = `
        <div class="export-modal">
            <h2>Export Feedback</h2>
            <label>Purpose: </label>
            <select name="Purpose" id="Purpose">
                <option value="All">All Purpose</option>
                <option value="Java">Java</option>
                <option value="C">C</option>
                <option value="C#">C#</option>
                <option value="C++">C++</option>
                <option value="Python">Python</option>
                <option value="PHP">PHP</option>
                <option value="ASP.NET">ASP.NET</option>
            </select>
            <label> Laboratory: </label>
            <select name="Laboratory" id="Laboratory">
                <option value="All">All Laboratory</option>
                <option value="524">524</option>
                <option value="526">526</option>
                <option value="530">530</option>
            </select>
            <p>Select the format you want to export:</p>
    <button onclick="exportFile('${source}', this)" value="csv" id="export-csv">CSV</button>
    <button onclick="exportFile('${source}', this)" value="pdf" id="export-pdf">PDF</button>
    <button onclick="exportFile('${source}', this)" value="excel" id="export-excel">EXCEL</button>

            <button onclick="closeOverlay()">Cancel</button>
        </div>
    `;

    document.body.appendChild(overlay);
}

function exportFile(type, button) {
    const buttonValue = button.value.toLowerCase(); // Get the actual value of the clicked button
    console.log("Export type:", type, "| Format:", buttonValue);

    if (type === 'history') {
        if (buttonValue === 'pdf') {
            fetchSitInHistoryData('pdf');
        } else if (buttonValue === 'excel') {
            fetchSitInHistoryData('excel');
        } else if (buttonValue === 'csv') {
            fetchSitInHistoryData('csv');
        } else {
            console.error("Unsupported export type:", buttonValue);
        }
    } else {
        if (buttonValue === 'pdf') {
            fetchAndExportPDF();
        } else if (buttonValue === 'excel') {
            fetchAndExportExcel();
        } else if (buttonValue === 'csv') {
            fetchAndExportCSV();
        } else {
            console.error("Unsupported export type:", buttonValue);
        }
    }
}
// ✅ Function to export data to CSV
function exportReportsToCSV(data, filename) {
    let csvContent = "data:text/csv;charset=utf-8,";

    // Define headers for the CSV (Name, Category, Count)
    const headers = ["Category", "Name", "Count"];
    csvContent += headers.join(",") + "\n";

    // Extract and format rows
    data.forEach(row => {
        // Ensure proper escaping of commas or quotes in data
        let rowData = headers.map(header => {
            let value = row[header];
            if (typeof value === 'string') {
                // Escape commas and double quotes within the value
                value = value.replace(/"/g, '""'); // Escape double quotes by doubling them
                if (value.indexOf(",") !== -1 || value.indexOf("\n") !== -1 || value.indexOf('"') !== -1) {
                    value = `"${value}"`; // Enclose values with commas or newlines in double quotes
                }
            }
            return value;
        });
        csvContent += rowData.join(",") + "\n";
    });

    // Create a Blob and trigger automatic download
    const blob = new Blob([csvContent], { type: "text/csv" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// ✅ Function to fetch and export CSV data
function fetchAndExportCSV() {
    const purpose = document.getElementById("Purpose").value;
    const lab = document.getElementById("Laboratory").value;

    console.log(purpose, lab);

    fetch(`./components/getAllReports.php?purpose=${purpose}&lab=${lab}`) // Updated to fetch from the new endpoint
        .then(response => response.json())
        .then(data => {
            let combinedData = [];

            // Merge purpose_counts
            Object.entries(data.purpose_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Purpose", Name: key, Count: value });
            });

            // Merge lab_counts
            Object.entries(data.lab_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Lab", Name: key, Count: value });
            });

            // Merge day_counts
            Object.entries(data.day_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Day", Name: key, Count: value });
            });

            // Export the combined data as CSV
            exportReportsToCSV(combinedData, "Reports.csv");
        })
        .catch(error => console.error("Error fetching data:", error));
}

// ✅ Function to export data to PDF
function exportReportsToPDF(data, filename) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Set up the document title and some general formatting
    doc.setFontSize(16);
    doc.text("Report Data", 14, 10); // Title at the top of the page

    // Extract headers and prepare rows for the table
    const headers = ["Category", "Name", "Count"];  // Explicit headers
    let rows = data.map(row => [row.Category, row.Name, row.Count]);

    // Add the table using autoTable from jsPDF
    doc.autoTable({
        head: [headers], // Column headers
        body: rows,      // Data rows
        startY: 20,      // Start after title
        theme: "grid",   // A theme to add gridlines
        margin: { top: 20, left: 14, right: 14 }, // Margin for the table
        headStyles: { fillColor: [22, 160, 133], textColor: 255, fontSize: 12 }, // Header style
        bodyStyles: { fontSize: 10 }, // Body text style
    });

    // Save the generated PDF with the specified filename
    doc.save(filename);
}

// ✅ Fetch and export data to PDF when the button is clicked
function fetchAndExportPDF() {
    const purpose = document.getElementById("Purpose").value;
    const lab = document.getElementById("Laboratory").value;

    console.log(purpose, lab); // Logging purpose and lab to verify the input

    fetch(`./components/getAllReports.php?purpose=${purpose}&lab=${lab}`) // Updated to fetch from the new endpoint
        .then(response => response.json())
        .then(data => {
            let combinedData = [];

            // Merge purpose_counts into combined data array
            Object.entries(data.purpose_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Purpose", Name: key, Count: value });
            });

            // Merge lab_counts into combined data array
            Object.entries(data.lab_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Lab", Name: key, Count: value });
            });

            // Merge day_counts into combined data array
            Object.entries(data.day_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Day", Name: key, Count: value });
            });

            // Call the function to export the combined data to a PDF
            exportReportsToPDF(combinedData, "Reports.pdf");
        })
        .catch(error => console.error("Error fetching data:", error));
}

// ✅ Function to export data to Excel
function exportToReportsExcel(data, filename) {
    const wb = XLSX.utils.book_new(); // Create a new workbook
    const ws = XLSX.utils.json_to_sheet(data); // Convert data to a sheet
    XLSX.utils.book_append_sheet(wb, ws, "Report"); // Append the sheet to the workbook

    // Optional: Style the sheet by setting the column width for better readability
    const wscols = [
        { wpx: 100 }, // Category column width
        { wpx: 150 }, // Name column width
        { wpx: 100 }  // Count column width
    ];
    ws['!cols'] = wscols; // Apply the column widths to the sheet

    // Write the file
    XLSX.writeFile(wb, filename);
}

// ✅ Fetch and export Excel with selected filters when the button is clicked
function fetchAndExportExcel() {
    const purpose = document.getElementById("Purpose").value;
    const lab = document.getElementById("Laboratory").value;

    console.log(purpose, lab); // Log selected filters to verify

    fetch(`./components/getAllReports.php?purpose=${purpose}&lab=${lab}`) // Fetch data with the selected filters
        .then(response => response.json())
        .then(data => {
            let combinedData = [];

            // Merge purpose_counts
            Object.entries(data.purpose_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Purpose", Name: key, Count: value });
            });

            // Merge lab_counts
            Object.entries(data.lab_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Lab", Name: key, Count: value });
            });

            // Merge day_counts
            Object.entries(data.day_counts).forEach(([key, value]) => {
                combinedData.push({ Category: "Day", Name: key, Count: value });
            });

            // Call the function to export the combined data to an Excel file
            exportToReportsExcel(combinedData, "Reports.xlsx");
        })
        .catch(error => console.error("Error fetching data:", error));
}

// Function to fetch data from the backend and export it in the chosen format
function fetchSitInHistoryData(format) {
    const purpose = document.getElementById("Purpose").value;
    const lab = document.getElementById("Laboratory").value;

    // Validate selections
    if (!purpose || !lab) {
        console.error("Both Purpose and Laboratory must be selected.");
        alert("Please select both Purpose and Laboratory before exporting.");
        return;
    }

    // Construct the URL with encoded parameters
    const url = `./components/getSitInHistory.php?purpose=${encodeURIComponent(purpose)}&lab=${encodeURIComponent(lab)}`;

    console.log("Fetching data from:", url); // Debugging the URL

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to fetch data. Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("Error in fetched data:", data.error);
                alert("Error fetching data: " + data.error);
            } else {
                console.log("Fetched data:", data); // Debugging data
                console.log(Array.isArray(data), data);
                exportData(data, format); // Pass format to exportData
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            alert("An error occurred while fetching data. Check the console for details.");
        });
}

function exportData(data, format) {
    console.log("Data length:", data.length);
    console.log("Sample row:", data[0]);

    if (Array.isArray(data) && typeof data[0] === "string") {
        console.log("Data is a stringified JSON array. Parsing...");
        data = JSON.parse(data[0]);
    }

    if (Array.isArray(data) && typeof data[0] === "object" && data[0] !== null) {
        console.log("Data is in correct format now.");
    } else {
        console.error("Data is not in expected format!", data);
        return;
    }

    if (format === 'csv') {
        exportToCSV(data, 'SitInHistory.csv');
    } else if (format === 'excel') {
        exportToExcel(data, 'SitInHistory.xlsx');
    } else if (format === 'pdf') {
        exportToPDF(data, 'SitInHistory.pdf');
    } else {
        console.error("Unsupported export format:", format);
    }
}

// Function to export data to CSV
function exportToCSV(data, filename) {
    let csvContent = "";

    // Add headers
    const headers = ["ID", "Name", "Purpose", "Lab", "Login", "Logout", "Date", "Feedback No", "History ID"];
    csvContent += headers.join(",") + "\n";

    // Add rows
    data.forEach(row => {
        console.log("Row data being added to CSV:", row);

        // Check if feedbackNo is null, and replace it with "No Feedback"
        const feedbackNo = row.feedbackNo === null ? "No Feedback" : row.feedbackNo;

        const rowData = [
            row.idNo,
            row.name,
            row.purpose,
            row.lab,
            row.login,
            row.logout,
            row.date,
            feedbackNo,  // Use the updated feedbackNo here
            row.historyID
        ];

        console.log("Row data being exported to CSV:", rowData);
        csvContent += rowData.join(",") + "\n";
    });

    // Create a Blob and trigger automatic download
    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Function to export data to Excel
function exportToExcel(data, filename) {
    // Modify data to replace null feedbackNo with "No Feedback"
    const modifiedData = data.map(row => {
        // If feedbackNo is null, replace it with "No Feedback"
        if (row.feedbackNo === null) {
            row.feedbackNo = "No Feedback";
        }
        return row;
    });

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(modifiedData); // Use modifiedData
    XLSX.utils.book_append_sheet(wb, ws, "SitInHistory");
    XLSX.writeFile(wb, filename);
}

// Function to export data to PDF
function exportToPDF(data, filename) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Define table headers
    const headers = ["ID", "Name", "Lab", "Purpose", "Date", "Login", "Logout"];

    // Format rows
    const rows = data.map(row => [
        row.idNo,
        row.name,
        row.lab,
        row.purpose,
        row.date,
        row.login,
        row.logout
    ]);

    // Add title with center alignment and customized font styles
    doc.setFontSize(16); // Set font size for the title
    doc.setFont("helvetica", "bold"); // Make font bold for the first title
    doc.setTextColor(0, 71, 171); // Blue color
    doc.text("University of Cebu-Main", 105, 20, { align: "center" }); // Center the first title

    doc.setTextColor(0, 0, 0); // Reset to black color for the rest of the text
    doc.setFont("helvetica", "normal"); // Normal font weight
    doc.setFontSize(12); // Smaller font size for the rest of the titles
    doc.text("College of Computer Studies", 105, 25, { align: "center" }); // Center this title
    doc.text("Computer Laboratory Sit-in Monitoring System Report", 105, 35, { align: "center" }); // Center this title

    // Add table to PDF
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 40, // Start the table below the titles
        headStyles: {
            fontSize: 12, // Font size for the table header
            fontStyle: 'bold', // Bold font for headers
            halign: 'center' // Center align table headers
        },
        bodyStyles: {
            fontSize: 10, // Font size for the table rows
            halign: 'center' // Center align table rows
        }
    });

    // Save PDF
    doc.save(filename);
}


//////////////////////////////////////////////////////////////////////////////////

//------------------------ FEEDBACK FORM SCRIPTS ------------------------------//

// FEEDBACK BUTTON
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("feedback-btn")) {
        let feedbackNo = event.target.getAttribute("data-feedbackNo");
        let idNo = event.target.getAttribute("data-id");

        // ✅ Fetch additional details (name and lab) from UserHistory.php
        let name = event.target.getAttribute("data-name");
        let lab = event.target.getAttribute("data-lab");
        let purpose = event.target.getAttribute("data-purpose");

        if (!feedbackNo || feedbackNo === "NULL") {
            // Show feedback form for new submission with name and lab
            showFeedbackForm(idNo, name, lab, purpose);
        } else {
            // Fetch existing feedback and display it
            fetchFeedback(feedbackNo);
        }
    }
});

function showFeedbackForm(idNo, name, lab, purpose) {
    let overlay = document.createElement("div");
    overlay.classList.add("overlay");

    overlay.innerHTML = `
        <div class="feedback-modal">
            <h2>Submit Feedback</h2>
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>Lab:</strong> ${lab}</p>
            <p><strong>Purpose:</strong> ${purpose}</p>
            <textarea id="feedbackText" placeholder="Enter your feedback..."></textarea>
            <br>
            <button onclick="submitFeedback('${idNo}', '${name}', '${lab}', '${purpose}')">Submit</button>
            <button onclick="closeOverlay()">Cancel</button>
        </div>
    `;

    document.body.appendChild(overlay);
}

// ✅ Function to submit feedback
function submitFeedback(idNo, name, lab, purpose) {
    let feedbackText = document.getElementById("feedbackText").value;
    console.log(purpose);
    fetch('./components/submitFeedback.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `idNo=${encodeURIComponent(idNo)}&feedback=${encodeURIComponent(feedbackText)}&name=${encodeURIComponent(name)}&lab=${encodeURIComponent(lab)}&purpose=${encodeURIComponent(purpose)}`
    })
        .then(response => response.text()) // ✅ Get raw response
        .then(text => {
            console.log("Raw Response:", text); // ✅ Debugging

            try {
                return JSON.parse(text); // ✅ Parse JSON only if valid
            } catch (error) {
                console.error("JSON Parsing Error:", error, "\nResponse was:", text);
                alert("Unexpected response from server. Check console for details.");
                return { error: "Invalid JSON response" }; // Prevent UI crash
            }
        })
        .then(data => {
            if (data.success) {
                alert("Feedback submitted successfully!");
                closeOverlay();
                location.reload(); // Refresh page to reflect changes
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
}

// ✅ Function to fetch and display existing feedback
function fetchFeedback(feedbackNo) {
    fetch(`./components/getFeedback.php?feedbackNo=${feedbackNo}`)
        .then(response => response.text()) // ✅ Get raw response first
        .then(text => {
            console.log("Raw Response:", text); // ✅ Log raw response for debugging

            try {
                return JSON.parse(text); // ✅ Parse JSON only if valid
            } catch (error) {
                console.error("JSON Parsing Error:", error, "\nRaw Response:", text);
                alert("Unexpected response from server. Check console for details.");
                return { error: "Invalid JSON response" }; // Prevent UI crash
            }
        })
        .then(data => {
            if (data.success) {
                let overlay = document.createElement("div");
                overlay.classList.add("overlay");

                // Create feedback modal structure
                let feedbackModal = document.createElement("div");
                feedbackModal.classList.add("feedback-modal");

                // Add content to the modal
                feedbackModal.innerHTML = `
                    <h2>Feedback</h2>
                    <p>${data.feedback}</p>
                    <button onclick="closeOverlay()">Close</button>
                `;

                // Apply color based on explicit feedback
                if (data.explicit === 'yes') {
                    feedbackModal.style.backgroundColor = "rgba(255, 0, 0, 0.1)"; // Red for explicit
                    feedbackModal.style.border = "2px solid red";
                } else {
                    feedbackModal.style.backgroundColor = "rgba(0, 128, 0, 0.1)"; // Green for non-explicit
                    feedbackModal.style.border = "2px solid green";
                }

                // Append the modal to the overlay and display
                overlay.appendChild(feedbackModal);
                document.body.appendChild(overlay);
            } else {
                alert("Error: " + data.error);
                console.error("Error Details:", data.error); // ✅ Log error details
            }
        })
        .catch(error => console.error("Fetch Error:", error));
}

// ✅ Function to close the overlay
function closeOverlay() {
    document.querySelector(".overlay")?.remove();
}

///////////////////////////////////////////////////////////////////////////////////////////////

//---------------------------------- CHART SCRIPTS -----------------------------------------//

document.addEventListener("DOMContentLoaded", function () {
    createPurposeChart();
    createLabChart();
    createDayChart();
});

// PIE CHART FUNCTION
function createPieChart(canvasId, labels, dataValues, colors) {
    const canvas = document.getElementById(canvasId);

    if (!canvas) {
        console.error(`Canvas with ID '${canvasId}' not found.`);
        return;
    }

    const ctx = canvas.getContext('2d');

    // Check if all values are 0
    const allZero = dataValues.every(value => value === 0);

    if (allZero) {
        dataValues = dataValues.map(() => 0.1); // Replace 0 values with small non-zero values
        colors = colors.map(() => "#D3D3D3"); // Light gray for "empty" sections
    }

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return allZero ? `${tooltipItem.label}: No Data` : `${tooltipItem.label}: ${tooltipItem.raw}`;
                        }
                    }
                }
            }
        }
    });
}

// PURPOSE PIE CHART FUNCTION
function createPurposeChart() {
    fetch('./components/getChartValues.php')
        .then(response => response.json())
        .then(values => {
            console.log(values);

            // Extract keys as labels and values as dataValues
            const labels = Object.keys(values);
            const dataValues = Object.values(values);

            // Define colors dynamically (or set a fixed array)
            const colors = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#A28DFF', '#00C49F', '#A28DFF']; // Add more if needed

            createPieChart('PurposeChart', labels, dataValues, colors.slice(0, labels.length));
        })
        .catch(error => console.error('Error fetching data:', error));
}

// LAB PIE CHART FUNCTION
function createLabChart() {
    fetch('./components/getLabChartValues.php')
        .then(response => response.json())
        .then(values => {
            console.log(values);

            // Extract keys as labels and values as dataValues
            const labels = Object.keys(values);
            const dataValues = Object.values(values);

            // Define colors dynamically (or set a fixed array)
            const colors = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#A28DFF', '#00C49F', '#A28DFF']; // Add more if needed

            createPieChart('LabChart', labels, dataValues, colors.slice(0, labels.length));
        })
        .catch(error => console.error('Error fetching data:', error));
}

// BAR CHART FUNCTION
function createBarChart(canvasId, labels, dataValues, colors) {
    const canvas = document.getElementById(canvasId);

    if (!canvas) {
        console.error(`Canvas with ID '${canvasId}' not found.`);
        return;
    }

    const ctx = canvas.getContext('2d');

    // Check if all values are 0
    const allZero = dataValues.every(value => value === 0);

    if (allZero) {
        dataValues = dataValues.map(() => 0.1); // Replace 0 values with small non-zero values
        colors = colors.map(() => "#D3D3D3"); // Light gray for "empty" bars
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 50,
                    ticks: {
                        stepSize: 10,
                        callback: function (value) {
                            return Math.floor(value); // Ensure no decimals are shown
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return allZero ? `${tooltipItem.label}: No Data` : `${tooltipItem.label}: ${Math.floor(tooltipItem.raw)}`;
                        }
                    }
                }
            }
        }
    });
}

// DAY BAR CHART FUNCTION
function createDayChart() {
    fetch('./components/getDayChartValues.php')
        .then(response => response.json())
        .then(values => {
            console.log(values);

            // Extract keys as labels and values as dataValues
            const labels = Object.keys(values);
            const dataValues = Object.values(values);

            // Define colors dynamically (or set a fixed array)
            const colors = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#A28DFF', '#00C49F', '#A28DFF']; // Add more if needed

            createBarChart('DayChart', labels, dataValues, colors.slice(0, labels.length));
        })
        .catch(error => console.error('Error fetching data:', error));
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// RESET SESSIONS BUTTON //
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("reset-btn")) {
        console.log("Button-Clicked!");
        const resetType = event.target.value; // Use the clicked button's value
        fetch(`./components/resetSessions.php?resetType=${resetType}`);
        this.location.reload();
    }
});

// SIGNOUT BUTTON //
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("signout-btn")) {
            let idNo = event.target.getAttribute("data-id");
            let studentId = event.target.getAttribute("data-student-id");

            if (confirm("Are you sure you want to sign out this user?")) {
                // Step 1: Sign Out the User
                fetch('./components/deleteSitIn.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'idNo=' + encodeURIComponent(idNo)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("User signed out successfully!");

                            // Step 2: Ask to give a point
                            if (confirm("Do you want to give this student 1 point?")) {
                                fetch('./components/givePoint.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: 'student_id=' + encodeURIComponent(studentId)
                                })
                                    .then(res => res.json())
                                    .then(resData => {
                                        if (resData.success) {
                                            alert("1 point awarded to student.");
                                        } else {
                                            alert("Failed to award point: " + resData.error);
                                        }
                                    })
                                    .catch(err => {
                                        console.error("Error giving point:", err);
                                        alert("Something went wrong while giving the point.");
                                    });
                            }

                            location.reload(); // Reload after sign out
                        } else {
                            alert("Error: " + data.error);
                        }
                    })
                    .catch(error => console.error('Sign-out Error:', error));
            }
        }
    });
});


// LOGOUT BUTTON //
function logout() {
    window.location.href = 'index.php';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//------------------------------ UPLOAD RESOURCES SCRIPTS -------------------------------//
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("upload-btn")) {
        console.log("Upload button clicked!");
        uploadFile();
    }
});

function uploadFile() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
        alert('Please select a file.');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch('./components/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === 'success') {
            alert('File uploaded successfully!');
            listFiles();
            // Clear the file input after successful upload
            fileInput.value = '';
        } else {
            alert('Upload failed: ' + response);
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Upload failed due to an error.');
    });
}

// Update the listFiles function to make files clickable
function listFiles() {
    console.log("Listing files");

    const userRole = document.body.getAttribute("data-role")?.toLowerCase();
    console.log("User role in file list:", userRole);

    fetch('./components/list_files.php')
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(files => {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';

        if (files.length === 0) {
            fileList.innerHTML = '<div class="no-files">No files available</div>';
            return;
        }

        files.forEach(file => {
            const div = document.createElement('div');
            div.className = 'file-item';
            
            // Make the entire file name clickable for download
            div.innerHTML = `
                <span class="downloadable-file" onclick="downloadFile('${file}')">
                    ${file}
                    ${userRole !== "student" ? `<button class="delete-btn" onclick="event.stopPropagation(); deleteFile('${file}')">🗑️</button>` : ''}
                </span>
            `;
            
            fileList.appendChild(div);
        });
    })
    .catch(error => {
        console.error('Error listing files:', error);
        document.getElementById('fileList').innerHTML = '<div class="error">Error loading files</div>';
    });
}

function downloadFile(filename) {
    // Create a temporary iframe to handle the download
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = `./components/download.php?file=${encodeURIComponent(filename)}`;
    document.body.appendChild(iframe);
    
    // Remove the iframe after a short delay
    setTimeout(() => {
        document.body.removeChild(iframe);
    }, 1000);
}

function deleteFile(filename) {
    if (!confirm(`Are you sure you want to delete "${filename}"?`)) {
        return;
    }

    fetch('./components/delete_file.php?file=' + encodeURIComponent(filename), {
        method: 'DELETE'
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === 'success') {
            alert('File deleted successfully.');
            listFiles();
        } else {
            alert('Delete failed: ' + response);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Delete failed due to an error.');
    });
}

// Call listFiles on page load to display existing files
document.addEventListener("DOMContentLoaded", function () {
    listFiles();
});

// Close overlay when clicking outside of it
document.addEventListener("click", function (event) {
    const overlay = document.querySelector(".overlay");
    if (overlay && !overlay.contains(event.target)) {
        closeOverlay();
    }
});

// Close overlay when clicking the cancel button
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("cancel-btn")) {
        closeOverlay();
    }
});

// Close overlay when clicking the close button
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("close-btn")) {
        closeOverlay();
    }
});

//////////////////////////////////////////////////////////////////////


///////////////<---- LAB SCHEDULE SCRIPTS ---->//////////////////////
function openModal(labNo) {
    document.getElementById('modal-' + labNo).style.display = 'flex';
}

function saveStatus(labNo) {
    const status = document.getElementById('status-' + labNo).value;

    fetch('./components/update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `lab_no=${labNo}&status=${status}`
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            document.getElementById('modal-' + labNo).style.display = 'none';
            location.reload(); // Refresh to show updated status
        });
}

// Close modal when clicking outside modal-content
window.addEventListener('click', function (event) {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// Auto-refresh every 60 seconds to update time-based status
setInterval(() => {
    location.reload();
}, 60000);
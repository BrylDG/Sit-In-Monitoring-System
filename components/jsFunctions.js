window.addEventListener('click', function (event) {
    const dropdown = document.getElementById('dropdownMenu');
    const profile = document.getElementById('Profile');
    if (!profile.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

document.addEventListener("click", function (event) {
    if (event.target.id === "addAnnouncement") {
        console.log("Add Announcement button clicked!"); // Debugging
        showAnnouncementForm();
    }
});


window.onload = function () {
    const userRole = document.body.getAttribute("data-role");
    console.log("User Role:", userRole);

    const defaultPage = (userRole === "admin") ? "AdminStatsRep.php" : "UserAnnouncements.php";
    const lastPage = sessionStorage.getItem('currentDashboard') || defaultPage;

    changeDashboard(lastPage);
    setActiveLink(lastPage);
};

function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');
}

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
                "UserProfile.php": "Profile"
            };

            const adminPages = {
                "AdminAnnouncements.php": "Announcements",
                "AdminFeedback.php": "Feedbacks",
                "AdminSitIn.php": "Sit-in",
                "AdminStatsRep.php": "Statistics & Reports",
                "AdminStudentList.php": "Student List",
                "UserProfile.php": "Profile"
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



function setActiveLink(page) {
    const links = document.querySelectorAll('nav a');

    links.forEach(link => {
        const linkHref = link.getAttribute('href').split('/').pop();
        const pageFile = page.split('/').pop();

        if (linkHref === pageFile) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

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

function showAnnouncementForm() {
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

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
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
        });
    } else {
        console.log("Waiting for searchInput to load...");
        setTimeout(initializeSearch, 500);
    }
}

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

// ✅ Ensure the search function initializes after DOM loads
document.addEventListener("DOMContentLoaded", function () {
    initializeSearch();
});

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

// ✅ Attach event listener to dynamically created buttons
document.addEventListener("click", function (event) {
    if (event.target.classList.contains("update-btn")) {
        submitSitInForm(event);
    }
});

document.addEventListener("click", function (event) {
    if (event.target.classList.contains("signout-btn")) {
        let idNo = event.target.getAttribute("data-id");

        if (confirm("Are you sure you want to sign out this user?")) {
            fetch('./components/deleteSitIn.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'idNo=' + encodeURIComponent(idNo)
            })
                .then(response => response.text()) // ✅ Get raw response for debugging
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
                        alert("User signed out successfully!");
                        document.getElementById("sitIn-" + idNo).remove(); // ✅ Remove entry from UI
                    } else {
                        alert("Error: " + data.error);
                        console.error("Error Details:", data.error);
                    }
                })
                .catch(error => console.error('Fetch Error:', error));
        }
    }
});

document.addEventListener("click", function (event) {
    if (event.target.classList.contains("feedback-btn")) {
        let feedbackNo = event.target.getAttribute("data-feedbackNo");
        let idNo = event.target.getAttribute("data-id");

        // ✅ Fetch additional details (name and lab) from UserHistory.php
        let name = event.target.getAttribute("data-name");
        let lab = event.target.getAttribute("data-lab");

        if (!feedbackNo || feedbackNo === "NULL") {
            // Show feedback form for new submission with name and lab
            showFeedbackForm(idNo, name, lab);
        } else {
            // Fetch existing feedback and display it
            fetchFeedback(feedbackNo);
        }
    }
});

// ✅ Function to display the feedback submission form (Now includes Name & Lab)
function showFeedbackForm(idNo, name, lab) {
    let overlay = document.createElement("div");
    overlay.classList.add("overlay");

    overlay.innerHTML = `
        <div class="feedback-modal">
            <h2>Submit Feedback</h2>
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>Lab:</strong> ${lab}</p>
            <textarea id="feedbackText" placeholder="Enter your feedback..."></textarea>
            <br>
            <button onclick="submitFeedback('${idNo}')">Submit</button>
            <button onclick="closeOverlay()">Cancel</button>
        </div>
    `;

    document.body.appendChild(overlay);
}

// ✅ Function to submit feedback
function submitFeedback(idNo, name, lab) {
    let feedbackText = document.getElementById("feedbackText").value;

    fetch('./components/submitFeedback.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `idNo=${encodeURIComponent(idNo)}&feedback=${encodeURIComponent(feedbackText)}&name=${encodeURIComponent(name)}&lab=${encodeURIComponent(lab)}`
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


// ✅ Function to close the overlay
function closeOverlay() {
    document.querySelector(".overlay")?.remove();
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

                overlay.innerHTML = `
                <div class="feedback-modal">
                    <h2>Previous Feedback</h2>
                    <p>${data.feedback}</p>
                    <button onclick="closeOverlay()">Close</button>
                </div>
            `;

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

document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript loaded! ✅");

    // Ensure buttons exist before adding event listeners
    setTimeout(() => {
        let buttons = document.querySelectorAll(".filter-btn");
        if (buttons.length === 0) {
            console.error("❌ No buttons found! Check PHP output.");
            return;
        }

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                let filterType = this.getAttribute("data-type");
                console.log("✅ Button clicked:", filterType);

                fetch("./components/fetchSitInData.php?type=" + filterType)
                    .then(response => response.json())
                    .then(data => {
                        console.log("📩 Data received:", data);

                        let sitInContainer = document.getElementById("sitInContainer");
                        sitInContainer.innerHTML = ""; // Clear previous content

                        if (data.length > 0) {
                            data.forEach(item => {
                                sitInContainer.innerHTML += `
                                    <div class="announcement-item" id="sitIn-${item.idNo}">
                                        <h3>${item.idNo}</h3>
                                        <p>${item.student_name}</p>
                                        <p>${item.purpose}</p>
                                        <p>${item.lab}</p>
                                        <small>${item.SitInTime}</small>
                                        <br>
                                        <button class="signout-btn" data-id="${item.idNo}">Sign Out</button>
                                        <hr>
                                    </div>
                                `;
                            });
                        } else {
                            sitInContainer.innerHTML = "<p>No Sit-In records found.</p>";
                        }
                    })
                    .catch(error => console.error("❌ Error fetching data:", error));
            });
        });

        console.log("✅ Buttons successfully attached!");
    }, 500);
});











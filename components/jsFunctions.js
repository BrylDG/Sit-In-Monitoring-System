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
    const userRole = "<?php echo $userRole; ?>".toLowerCase();
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
            const userRole = "<?php echo trim($userRole); ?>".toLowerCase();

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

            console.log("User Role:", userRole);

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





window.addEventListener('click', function (event) {
    const dropdown = document.getElementById('dropdownMenu');
    const profile = document.getElementById('Profile');
    if (!profile.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

window.onload = function () {
    const lastPage = sessionStorage.getItem('currentDashboard') || 'UserAnnouncements.php';
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
        })
        .catch(error => console.error('Error loading component:', error));
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

    // If edit button is visible, enable editing
    if (editBtn.style.display !== 'none') {
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.classList.add('editable');
        });
        editBtn.style.display = 'none';
        updateBtn.style.display = 'inline-block';
    } else {
        // On update click, disable editing
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.remove('editable');
        });
        updateBtn.style.display = 'none';
        editBtn.style.display = 'inline-block';
    }
}



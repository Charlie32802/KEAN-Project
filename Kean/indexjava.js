// Image Preview Logic
function previewImage(event) {
    const reader = new FileReader();
    const preview = document.getElementById('profilePreview');

    reader.onload = function() {
        preview.src = reader.result;
    };

    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

// Handle login form submission (you can add AJAX here for more seamless user experience)
document.getElementById('loginForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Send login credentials to login.php using AJAX
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == "success") {
                window.location.href = "dashboard.php";  // Redirect to dashboard on successful login
            } else {
                alert("Invalid login credentials.");
            }
        }
    };
    xhr.send(formData);
};

// Toggle between login and register forms
document.getElementById('showRegisterLink').onclick = function() {
    document.getElementById('loginContainer').style.display = 'none';
    document.getElementById('registerContainer').style.display = 'block';
    resetProfilePicture();
};

document.getElementById('showLoginLink').onclick = function() {
    document.getElementById('registerContainer').style.display = 'none';
    document.getElementById('loginContainer').style.display = 'block';
};

// Reset profile picture to default when switching forms
function resetProfilePicture() {
    document.getElementById('profilePreview').src = 'default-profile-pic.jpg';
    document.getElementById('profilePicture').value = '';
    document.getElementById('passwordError').style.display = 'none'; // Hide error message on switch
}

// Handle registration form submission
document.getElementById('registerForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent the default form submission

    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const errorMessage = document.getElementById('passwordError');

    // Check if passwords match
    if (password !== confirmPassword) {
        errorMessage.style.display = 'block'; // Show error message
        return; // Stop the form submission
    } else {
        errorMessage.style.display = 'none'; // Hide error message if passwords match
    }

    const profilePicture = document.getElementById('profilePicture').value;

    // If no profile picture, show modal
    if (!profilePicture) {
        showModal();
    } else {
        // If profile picture is provided, submit the form directly
        this.submit();
    }
};

// Show modal
function showModal() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('modal').style.display = 'block';
    document.getElementById('mainContent').classList.add('blur'); // Blur background
}

// Hide modal
function hideModal() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('modal').style.display = 'none';
    document.getElementById('mainContent').classList.remove('blur'); // Unblur background
}

// Handle Yes button in modal (Proceed without profile picture)
document.getElementById('yesButton').onclick = function() {
    hideModal();
    // Submit the form after confirmation (proceed to success.html)
    document.getElementById('registerForm').submit();
};

// Handle No button in modal (Stay on registration form)
document.getElementById('noButton').onclick = function() {
    hideModal(); // Close modal without submitting the form
};

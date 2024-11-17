document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');

    if (!emailInput.value.includes('@') || !emailInput.value.includes('.')) {
        emailError.style.display = 'block';
        event.preventDefault();
    } else {
        emailError.style.display = 'none';
    }
});

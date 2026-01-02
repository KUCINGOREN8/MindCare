window.togglePassword = function(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + "-eye");

    if (!passwordField || !eyeIcon) return;

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.src = eyeIcon.dataset.openIcon;
    } else {
        passwordField.type = "password";
        eyeIcon.src = eyeIcon.dataset.closedIcon;
    }
}

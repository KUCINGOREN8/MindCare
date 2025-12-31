function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeIcon = field.parentElement.querySelector('.eye-icon');

    if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.src = eyeIcon.src.replace('eye-closed.svg', 'eye-open.svg');
    } else {
        field.type = 'password';
        eyeIcon.src = eyeIcon.src.replace('eye-open.svg', 'eye-closed.svg');
    }
}

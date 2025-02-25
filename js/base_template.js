const themeToggleCheckbox = document.getElementById("checkbox");
const currentTheme = localStorage.getItem('theme') || 'light';

document.documentElement.setAttribute('data-bs-theme', currentTheme); 

checkbox.addEventListener("change", () => {
const newTheme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
});
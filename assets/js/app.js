import "bootstrap"; // prend bootstrap depuis node_modules

document.addEventListener("DOMContentLoaded", () => {
    console.log("JS OK (esbuild)");
    const triggers = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...triggers].forEach((el) => new bootstrap.Tooltip(el));
});


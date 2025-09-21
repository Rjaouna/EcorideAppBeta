import "bootstrap/dist/js/bootstrap.bundle.js";

document.addEventListener("DOMContentLoaded", () => {
    console.log("JS OK (esbuild)");
    const triggers = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...triggers].forEach((el) => new bootstrap.Tooltip(el));
});

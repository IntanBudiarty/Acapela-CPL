document.addEventListener("DOMContentLoaded", () => {
    const dosenLoginButton = document.getElementById("login-dosen"); // Ubah ID sesuai dengan yang ada di HTML
    const adminLoginButton = document.getElementById("login-admin"); // Ubah ID sesuai dengan yang ada di HTML
    const container = document.getElementById("container");

    if (dosenLoginButton && adminLoginButton && container) {
        dosenLoginButton.addEventListener("click", () => {
            container.classList.remove("right-panel-active");
        });

        adminLoginButton.addEventListener("click", () => {
            container.classList.add("right-panel-active");
        });
    } else {
        console.error("One or more elements could not be found in the DOM.");
    }
});

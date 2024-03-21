document.addEventListener("DOMContentLoaded", function () {
    const mobileScreen = window.matchMedia("(max-width: 990px)");

    // Menü toggle a menü ikonra kattintva
    document.querySelector(".menu-toggle-dashboard").addEventListener("click", () => {
        console.log("kattintva")
        const dashboard = document.querySelector(".dashboard");
        const dashboardNav = document.querySelector(".dashboard-nav");

        const mobileMenu = document.getElementById("mobileMenu");

        if (mobileScreen.matches) {
            // Mobil nézetben a menü teljes megjelenítése vagy elrejtése
            dashboardNav.classList.toggle("mobile-show");


        } else {
            // Asztali nézetben a teljes menü szűkített vagy normál nézete
            dashboard.classList.toggle("dashboard-compact");
        }
    });

    document.getElementById("mobileMenu").addEventListener("click", () => {
        const dashboardNav = document.querySelector(".dashboard-nav");
        if (mobileScreen.matches) {
            // Mobil nézetben a menü teljes megjelenítése vagy elrejtése
            dashboardNav.classList.toggle("mobile-show");
        }
    });

    // Dropdown toggle
    const dropdownToggles = document.querySelectorAll(".dashboard-nav-dropdown-toggle");
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", function (event) {
            event.preventDefault(); // Megakadályozza a hivatkozás alapértelmezett viselkedését
            const currentDropdown = this.parentElement;
            // Aktuális dropdown megjelenítése/elrejtése
            currentDropdown.classList.toggle("show");
            // Más dropdownok elrejtése
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    otherToggle.parentElement.classList.remove("show");
                }
            });
        });
    });

    // Ha a felhasználó más helyre kattint, rejtse el a dropdown menüket
    document.body.addEventListener("click", function (e) {
        if (!e.target.matches('.dashboard-nav-dropdown-toggle')) {
            dropdownToggles.forEach(toggle => {
                toggle.parentElement.classList.remove("show");
            });
        }
    }, true);
});

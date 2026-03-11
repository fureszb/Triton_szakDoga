document.addEventListener("DOMContentLoaded", function () {
    const mobileScreen = window.matchMedia("(max-width: 990px)");
    const dashboardNav = document.querySelector(".dashboard-nav");
    const dashboard = document.querySelector(".dashboard");

    // Overlay létrehozása mobil menühöz
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    function openMobileNav() {
        dashboardNav.classList.add("mobile-show");
        overlay.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    function closeMobileNav() {
        dashboardNav.classList.remove("mobile-show");
        overlay.classList.remove("active");
        document.body.style.overflow = "";
    }

    // Topbar hamburger gomb
    document.querySelector(".menu-toggle-dashboard").addEventListener("click", () => {
        if (mobileScreen.matches) {
            if (dashboardNav.classList.contains("mobile-show")) {
                closeMobileNav();
            } else {
                openMobileNav();
            }
        } else {
            dashboard.classList.toggle("dashboard-compact");
        }
    });

    // Sidebar belső X gomb (mobil)
    document.getElementById("mobileMenu").addEventListener("click", () => {
        if (mobileScreen.matches) {
            closeMobileNav();
        }
    });

    // Overlay kattintásra bezár
    overlay.addEventListener("click", () => {
        closeMobileNav();
    });

    // ESC gombra bezár
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && dashboardNav.classList.contains("mobile-show")) {
            closeMobileNav();
        }
    });

    // Ablak átméretezéskor kompakt mód eltávolítása mobilon
    window.addEventListener("resize", () => {
        if (!mobileScreen.matches && dashboardNav.classList.contains("mobile-show")) {
            closeMobileNav();
        }
    });

    // Dropdown toggle
    const dropdownToggles = document.querySelectorAll(".dashboard-nav-dropdown-toggle");
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", function (event) {
            event.preventDefault();
            const currentDropdown = this.parentElement;
            currentDropdown.classList.toggle("show");
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    otherToggle.parentElement.classList.remove("show");
                }
            });
        });
    });

    document.body.addEventListener("click", function (e) {
        if (!e.target.matches('.dashboard-nav-dropdown-toggle')) {
            dropdownToggles.forEach(toggle => {
                toggle.parentElement.classList.remove("show");
            });
        }
    }, true);
});

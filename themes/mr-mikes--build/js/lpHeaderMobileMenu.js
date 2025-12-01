// TODO: Move it later to the TypeScript

document.addEventListener("DOMContentLoaded", () => {

    // Getting the Menu Button
    const mobileMenuToggleBtnEl = document.querySelector(".mrm-lp-header__burger-btn");
    if (!mobileMenuToggleBtnEl) {
        return;
    }

    // Getting Menu Root/Wrapper El
    const mobileMenuRootEl = document.querySelector(".mrm-lp-header-right");
    if (!mobileMenuRootEl) {
        return;
    }

    let isScrollBlocked = false;

    mobileMenuToggleBtnEl.addEventListener("click", () => {

        if (!isScrollBlocked) {
            // Making disabled Page Scroll
            document.body.style.overflowY = "hidden";

            // <html> overflow: hidden to fix the Safari overflow bug
            document.documentElement.style.overflowY = "hidden";

            isScrollBlocked = true;
        } else {
            // Making disabled Page Scroll
            document.body.style.overflowY = "";

            // <html> overflow: hidden to fix the Safari overflow bug
            document.documentElement.style.overflowY = "";

            isScrollBlocked = false;
        }

        mobileMenuToggleBtnEl.classList.toggle("is-active");
        mobileMenuRootEl.classList.toggle("mrm-lp-header-right_active");
    });


    // Smooth Scrolling for the LP Header Menu
    const navLinks = document.querySelectorAll(".mrm-lp-header-right-menu__item, .mrm-lp-header-right__cta-btn");
    if (!navLinks) {
        return;
    }

    console.log(navLinks);

    navLinks.forEach(navLink => {

        navLink.addEventListener("click", evt => {
            evt.preventDefault();

            // Checking if it is the Mobile device
            if (window.screen.width <= 1199) {

                if (isScrollBlocked) {
                    // Making disabled Page Scroll
                    document.body.style.overflowY = "";

                    // <html> overflow: hidden to fix the Safari overflow bug
                    document.documentElement.style.overflowY = "";

                    isScrollBlocked = false;
                }

                if (mobileMenuToggleBtnEl.classList.contains("is-active")) {
                    mobileMenuToggleBtnEl.classList.remove("is-active");
                }

                if (mobileMenuRootEl.classList.contains("mrm-lp-header-right_active")) {
                    mobileMenuRootEl.classList.remove("mrm-lp-header-right_active");
                }

            }

            const scrollToElId = evt.target.getAttribute("href");
            if (!scrollToElId) {
                return;
            }

            const scrollToEl = document.querySelector(scrollToElId);

            if (!scrollToEl) {
                return;
            }

            const lpHeader = document.querySelector(".mrm-lp-header");
            if (!lpHeader) {
                return;
            }

            // Scroll to element behaviour
            const scrollToElYCoord = scrollToEl.getBoundingClientRect().top + window.scrollY - lpHeader.offsetHeight;
            window.scrollTo({
                top: scrollToElYCoord,
                behavior: "smooth"
            });

        });

    });

});
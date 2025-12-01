document.addEventListener("DOMContentLoaded", () => {

    // Getting the Accordion Elements
    const accordionSectionEls = document.querySelectorAll(".mrm-accordion");

    if (accordionSectionEls.length === 0) {
        return;
    }

    accordionSectionEls.forEach(accordionSectionEl => {

        const accordionItemEls = accordionSectionEl.querySelectorAll(".mrm-accordion-items__item");
        if (accordionItemEls.length === 0) {
            return;
        }

        accordionItemEls.forEach(accordionItemEl => {
            accordionItemEl.addEventListener("click", () => {
                accordionItemEl.classList.toggle("mrm-accordion-items__item_active");

                const accordionItemBody = accordionItemEl.querySelector(".mrm-accordion-items__item-body");

                if (!accordionItemBody) {
                    return;
                }

                if (accordionItemBody.style.maxHeight) {
                    accordionItemBody.style.maxHeight = "";
                } else {
                    accordionItemBody.style.maxHeight = accordionItemBody.scrollHeight + "px";
                }
            });

        });

    });

});
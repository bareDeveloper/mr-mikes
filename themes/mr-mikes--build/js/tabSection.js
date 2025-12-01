document.addEventListener("DOMContentLoaded", () => {
  const tabContainer = document.querySelector(".giftcard__tab");
  const tabLinks = document.querySelectorAll(".tablinks");
  const tabContents = document.querySelectorAll(".tabcontent");

  if (!tabContainer || tabLinks.length === 0 || tabContents.length === 0) {
      return;
  }

  const baseUrl = tabContainer.getAttribute('data-base-url').replace(/\/$/, ''); // Remove trailing slash if exists

  function updateUrl(tabLink) {
      const fullPath = tabLink.dataset.fullPath;
      const newPath = fullPath || baseUrl;
      window.history.pushState({ path: newPath }, '', newPath);
  }

  function changeTab(tabLink) {
      tabContents.forEach(tabContent => tabContent.style.display = "none");
      tabLinks.forEach(tab => tab.classList.remove("active"));

      const tabName = tabLink.getAttribute('data-tab');
      const activeTabContent = document.getElementById(tabName);
      if (activeTabContent) {
          activeTabContent.style.display = "block";
      }

      tabLink.classList.add("active");
      updateUrl(tabLink);
  }

  tabLinks.forEach(tabLink => {
      tabLink.addEventListener("click", () => changeTab(tabLink));
  });

  // Handle browser back and forward buttons
  window.onpopstate = function(event) {
      const path = window.location.pathname.replace(baseUrl, '').replace(/^\//, '');
      const targetTab = [...tabLinks].find(tab => {
          const fullPath = tab.dataset.fullPath || baseUrl;
          return fullPath.replace(baseUrl, '').replace(/^\//, '') === path;
      });
      if (targetTab) {
          changeTab(targetTab);
      }
  };
});

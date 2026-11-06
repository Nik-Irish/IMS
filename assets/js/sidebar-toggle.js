'use strict';

const app = document.querySelector('.app');
const hideNavigationButton = document.querySelector('.sidebar .sidebar-toggle');
const showNavigationButton = document.querySelector('.sidebar-toggle-show');
const sidebarPreferenceKey = 'ims.sidebar.collapsed';

let isSidebarCollapsed = false;

try {
  isSidebarCollapsed = window.localStorage.getItem(sidebarPreferenceKey) === 'true';
} catch (error) {
  console.warn('Unable to read the navigation visibility preference.', error);
}

function setSidebarCollapsed(collapsed) {
  isSidebarCollapsed = collapsed;
  app.classList.toggle('sidebar-collapsed', collapsed);
  hideNavigationButton.hidden = collapsed;
  showNavigationButton.hidden = !collapsed;
  hideNavigationButton.setAttribute('aria-expanded', String(!collapsed));
  showNavigationButton.setAttribute('aria-expanded', String(collapsed));

  try {
    window.localStorage.setItem(sidebarPreferenceKey, String(collapsed));
  } catch (error) {
    console.warn('Unable to save the navigation visibility preference.', error);
  }
}

hideNavigationButton.addEventListener('click', () => {
  setSidebarCollapsed(true);
});

showNavigationButton.addEventListener('click', () => {
  setSidebarCollapsed(false);
});

setSidebarCollapsed(isSidebarCollapsed);

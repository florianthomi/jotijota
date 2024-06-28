import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import { Turbo } from "@hotwired/turbo-rails"
Turbo.session.drive = false

const root = document.getElementsByTagName('html')[0];

const clickOutside = (element) => {
  return (event) => {
    if (!element.parentElement.contains(event.target)) {
      toggleDropdown(element)
    }
  }
}

function toggleDropdown(element) {
  if (element.dataset.dropdown === 'close') {
    element.dataset.dropdown = 'open';
    if (element.dataset.hasOwnProperty('dropdownClickoutside')) {
      element._clickOutsideHandler = clickOutside(element); // store the handler on the element
      document.addEventListener('click', element._clickOutsideHandler);
    }
  } else {
    element.dataset.dropdown = 'close';
    if (element._clickOutsideHandler) {
      document.removeEventListener('click', element._clickOutsideHandler);
      element._clickOutsideHandler = null; // clear the stored handler
    }
  }
}

function toggleDarkMode() {
  if (root.dataset.darkMode === '1') {
    root.dataset.darkMode = '0';
  } else {
    root.dataset.darkMode = '1';
  }

  localStorage.setItem('darkMode', root.dataset.darkMode)
}

document.querySelectorAll('[data-dropdown]').forEach((element) => {
  element.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleDropdown(element);
  });
});

if (document.getElementById('dark-mode-toggler')) {
  document.getElementById('dark-mode-toggler').addEventListener('click', toggleDarkMode)
}

document.querySelectorAll('.hamburger-toggler').forEach((element) => {
  element.addEventListener('click', (e) => {
    e.stopPropagation();
    document.body.classList.toggle('sidebar-open')
  })
})

document.addEventListener('DOMContentLoaded', () => root.dataset.darkMode = localStorage.getItem('darkMode') ?? '0')

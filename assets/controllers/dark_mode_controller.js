import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  toggle() {
    const isCurrentlyDark = document.documentElement.classList.contains("dark");
    this.applyDarkMode(!isCurrentlyDark);
    this.setUserPreference(!isCurrentlyDark);
  }

  applyDarkMode(isDark) {
    if (isDark) {
      document.documentElement.classList.add("dark");
    } else {
      document.documentElement.classList.remove("dark");
    }
  }

  getUserPreference() {
    return localStorage.getItem("dark-mode");
  }

  setUserPreference(isDark) {
    localStorage.setItem("dark-mode", isDark ? "true" : "false");
  }
}

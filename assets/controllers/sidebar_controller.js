import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = [ "sidebarContainer" ]

  toggle() {
    if (this.sidebarContainerTarget.dataset.expanded === "1") {
      this.collapse()
    } else {
      this.expand()
    }
  }

  collapse() {
    this.sidebarContainerTarget.classList.remove("translate-x-0")
    this.sidebarContainerTarget.dataset.expanded = "0"
  }

  expand() {
    this.sidebarContainerTarget.classList.add("translate-x-0")
    this.sidebarContainerTarget.dataset.expanded = "1"
  }
}

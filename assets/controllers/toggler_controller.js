import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = [ "background", "cursor", "input" ]

  connect() {
    if (this.inputTarget.checked) {
      this.enable()
    } else {
      this.disable()
    }
  }

  toggle() {
    if (this.inputTarget.checked) {
      this.enable()
    } else {
      this.disable()
    }
  }

  enable() {
    this.backgroundTarget.classList.add("!bg-primary")
    this.cursorTarget.classList.add("!right-1", "!translate-x-full")
  }

  disable() {
    this.backgroundTarget.classList.remove("!bg-primary")
    this.cursorTarget.classList.remove("!right-1", "!translate-x-full")
  }
}

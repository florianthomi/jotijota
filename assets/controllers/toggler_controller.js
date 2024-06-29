import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = [ "background", "cursor", "input" ]

  connect() {
    this.toggle()
  }

  toggle() {
    if (this.inputTarget.checked) {
      this.enable()
    } else {
      this.disable()
    }
  }

  enable() {
    console.log('enable')
    this.inputTarget.checked = true
    this.backgroundTarget.classList.add("!bg-primary")
    this.cursorTarget.classList.add("!right-1", "!translate-x-full")
  }

  disable() {
    this.inputTarget.checked = false
    this.backgroundTarget.classList.remove("!bg-primary")
    this.cursorTarget.classList.remove("!right-1", "!translate-x-full")
  }
}

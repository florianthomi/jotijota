import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = [ "menu", "icon" ]

  toggle() {
    if (this.menuTarget.classList.contains('hidden')){
      this.open()
    } else {
      this.close()
    }
  }

  open() {
    this.menuTarget.classList.remove('hidden')
    this.iconTarget.classList.add('rotate-180')
    window.addEventListener("click", this.outsideClickListener);
  }

  close() {
    this.menuTarget.classList.add('hidden')
    this.iconTarget.classList.remove('rotate-180')
    window.removeEventListener("click", this.outsideClickListener);
  }

  outsideClickListener = (event) => {
    if (!this.element.contains(event.target) && !this.menuTarget.classList.contains('hidden')) {
      this.close();
    }
  }
}

import { Controller } from "@hotwired/stimulus"

export default class AnimatedNumber extends Controller {
  static values = {
    start: Number,
    end: Number,
    duration: Number
  }

  connect() {
    this.animate()
  }

  animate() {
    let startTimestamp = null

    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp

      const elapsed = timestamp - startTimestamp
      const progress = Math.min(elapsed / this.durationValue, 1)

      this.element.innerHTML = Math.floor(progress * (this.endValue - this.startValue) + this.startValue).toString()

      if (progress < 1) {
        window.requestAnimationFrame(step)
      }
    }

    window.requestAnimationFrame(step)
  }
}

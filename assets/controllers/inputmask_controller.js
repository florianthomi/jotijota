import { Controller } from "@hotwired/stimulus";
import Inputmask from "inputmask/dist/inputmask.es6.js";

export default class extends Controller {
  connect() {
    this.element.querySelectorAll("input[data-inputmask-regex]").forEach((el) => {
        const im = new Inputmask({casing: (element) => element.toUpperCase(), regex: el.dataset['inputmask-regex']});
        im.mask(el)
    })
  }
}

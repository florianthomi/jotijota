import Inputmask from "inputmask";

Inputmask({
  casing: function (elem) {
    return elem.toUpperCase()
  }
}).mask(document.querySelectorAll("input[data-inputmask-regex]"));

let form = document.getElementById('form_entry');

form.addEventListener('submit', (e) => {
  e.preventDefault();
  const options = {
    method: form.attributes.method.value,
    body: new FormData(form),
  }
  fetch(form.baseURI, options).then((r) => {
    return r.text()
  }).then((data) => {
    let dom = new DOMParser();
    let html = dom.parseFromString(data, 'text/html');
    form.innerHTML = html.getElementById('form_entry').innerHTML
    document.getElementById('entries').innerHTML = html.getElementById('entries').innerHTML
    Inputmask({
      casing: function (elem, test, pos, validPositions) {
        return elem.toUpperCase()
      }
    }).mask(document.querySelectorAll("input[data-inputmask-regex]"));

    let event = new Event('refresh');
    Array.from(document.getElementsByTagName('canvas')).forEach((e) => e.dispatchEvent(event))
  }).error(() => {})
})



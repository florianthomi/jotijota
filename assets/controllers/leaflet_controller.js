import { Controller } from "@hotwired/stimulus";
import * as leaflet from 'leaflet'
import 'leaflet/dist/leaflet.min.css'

export default class extends Controller {
  static targets = [ "map" ]
  static values = {
    points: Array,
    markerIcon: String
  }

  map = null;

  connect() {
    if (this.mapTarget) {
      this.map = leaflet.map(this.mapTarget).setView([0,0], 2)
      leaflet.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(this.map);

      const icon = leaflet.icon({
        iconUrl: this.markerIconValue
      })

      const group = new leaflet.featureGroup();

      this.pointsValue.forEach(({lat, lng}) => group.addLayer(leaflet.marker([lat, lng], {icon: icon})))

      if (group.getLayers().length) {
        group.addTo(this.map)
        this.map.fitBounds(group.getBounds());
      }
    }
  }
}

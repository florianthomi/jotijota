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
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        className: 'map-tiles'
      }).addTo(this.map);

      const icon = leaflet.icon({
        iconUrl: this.markerIconValue,
        popupAnchor: [19, 0]
      })

      const group = new leaflet.FeatureGroup();

      this.pointsValue.filter(({lat, lng}) => lat !== 0 && lng !== 0).forEach(({lat, lng, title}) => {
        let marker = leaflet.marker([lat, lng], {icon: icon})
        if (title) {
        marker.bindPopup('<strong class="text-xl">'+title+'</strong>')
            .openPopup();
        }
        group.addLayer(marker)
      })

      if (group.getLayers().length) {
        group.addTo(this.map)
        this.map.fitBounds(group.getBounds());
      }
    }
  }
}

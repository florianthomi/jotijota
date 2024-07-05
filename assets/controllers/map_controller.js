import { Controller } from "@hotwired/stimulus";
import * as L from 'leaflet'
import 'leaflet/dist/leaflet.min.css'

export default class extends Controller {
  static targets = [ "canvas" ]
  static values = {
    leaflet: {
      type: Boolean,
      default: false
    },
    counts: {
      type: Object,
      default: {}
    },
    url: String
  }

  leaflet(canvas, data, values) {
    const map = L.map(canvas).setView([0, 0], 2);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      className: 'map-tiles',
    }).addTo(map);

    let geojson;

    const getColor = (d) => {
      return d > 1000 ? '#800026' :
        d > 500  ? '#BD0026' :
          d > 200  ? '#E31A1C' :
            d > 100  ? '#FC4E2A' :
              d > 50   ? '#FD8D3C' :
                d > 20   ? '#FEB24C' :
                  d > 10   ? '#FED976' :
                    '#FFEDA0';
    }

    const style = (feature) => {
      return {
        fillColor: getColor(values[feature.properties.iso_a2] ?? 0),
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7
      };
    }

    const highlightFeature = (e) => {
      let layer = e.target;

      layer.setStyle({
        weight: 3,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
      });

      layer.bringToFront();
      info.update(layer.feature.properties);
    }

    const resetHighlight = (e) => {
      geojson.resetStyle(e.target);
      info.update()
    }

    const zoomToFeature = (e) => map.fitBounds(e.target.getBounds());

    const onEachFeature = (feature, layer) => {
      layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
      });
    }

    geojson = L.geoJson(data, {
      style: style,
      onEachFeature: onEachFeature
    }).addTo(map);


    let info = L.control();
    info.onAdd = function (map) {
      this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
      this.update();
      return this._div;
    }

    info.update = function (props) {
      this._div.innerHTML = '<h4>Contacts</h4>' +  (props ?
        '<b>' + props.name + '</b><br />' + (values[props.iso_a2] ?? 0) + ' contact(s)'
        : 'Hover over a country');
    };

    info.addTo(map);

    let legend = L.control({position: 'bottomright'});

    legend.onAdd = function (map) {

      let div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 10, 20, 50, 100, 200, 500, 1000],
        labels = [];

      // loop through our density intervals and generate a label with a colored square for each interval
      for (let i = 0; i < grades.length; i++) {
        div.innerHTML +=
          '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
          grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
      }

      return div;
    };

    legend.addTo(map);
  }

  connect() {
    const jsonUrl = this.urlValue;
    const canvas = this.canvasTarget
    fetch(jsonUrl)
      .then(response => response.json())
      .then(data => {
          this.leaflet(canvas, data, this.countsValue)
      })
      .catch(error => console.error('Error fetching JSON:', error));
  }
}

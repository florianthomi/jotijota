import {Controller} from "@hotwired/stimulus";
import * as L from 'leaflet'
import 'leaflet/dist/leaflet.min.css'

export default class extends Controller {
  static targets = ["canvas"]
  static values = {
    counts: {
      type: Object,
      default: {}
    },
    url: String
  }

  map = null
  countries = {}
  geojson = null
  info = null
  legend = null


  getColor = (d) => {
    return d > 1000 ? '#800026' :
      d > 500 ? '#BD0026' :
        d > 200 ? '#E31A1C' :
          d > 100 ? '#FC4E2A' :
            d > 50 ? '#FD8D3C' :
              d > 20 ? '#FEB24C' :
                d > 10 ? '#FED976' :
                  d > 0 ? '#FFEDA0' :
                    '#fefce8';
  }

  style = (feature) => {
    return {
      fillColor: this.getColor(this.countsValue[feature.properties.alpha2] ?? 0),
      weight: 2,
      opacity: 1,
      color: 'white',
      dashArray: '3',
      fillOpacity: 0.7
    };
  }

  highlightFeature = (e) => {
    let layer = e.target;

    layer.setStyle({
      weight: 3,
      color: '#666',
      dashArray: '',
      fillOpacity: 0.7
    });

    layer.bringToFront();
    this.info.update(layer.feature.properties);
  }

  resetHighlight = (e) => {
    this.geojson.resetStyle(e.target);
    this.info.update()
  }

  zoomToFeature = (e) => this.map.fitBounds(e.target.getBounds());

  onEachFeature = (feature, layer) => {
    layer.on({
      mouseover: this.highlightFeature,
      mouseout: this.resetHighlight,
      click: this.zoomToFeature
    });
  }

  initMap () {
    this.map = L.map(this.canvasTarget).setView([0, 0], 2);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      className: 'map-tiles',
    }).addTo(this.map);
  }


  setGeoJsonLayer () {
    if (this.geojson) {
      this.map.removeLayer(this.geojson)
    }
    this.geojson = L.geoJson(this.countries, {
      style: this.style,
      onEachFeature: this.onEachFeature
    }).addTo(this.map);
  }


  initInfo () {
    if (this.info) {
      this.map.removeLayer(this.info)
    }
    this.info = L.control();
    this.info.onAdd = function (map) {
      this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
      this.update();
      return this._div;
    }

    this.info.update = (props) => {
      this.info._div.innerHTML = '<h4>Contacts</h4>' + (props ?
        '<b>' + props['name:' + document.documentElement.lang] + '</b><br />' + (this.countsValue[props.alpha2] ?? 0) + ' contact(s)'
        : 'Hover over a country');
    };

    this.info.addTo(this.map);

    if (this.legend) {
      this.map.removeLayer(this.legend)
    }
    this.legend = L.control({position: 'bottomright'});

    this.legend.onAdd = (map) => {

      let div = L.DomUtil.create('div', 'info legend'),
        grades = [1, 10, 20, 50, 100, 200, 500, 1000],
        labels = [];

      // loop through our density intervals and generate a label with a colored square for each interval
      for (let i = 0; i < grades.length; i++) {
        div.innerHTML +=
          '<i style="background:' + this.getColor(grades[i] + 1) + '"></i> ' +
          grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
      }

      return div;
    };

    this.legend.addTo(this.map);
  }

  countsValueChanged (value, previousValue) {
    if (this.map) {
      this.setGeoJsonLayer()
    }
  }

  connect () {
    fetch(this.urlValue)
      .then(response => response.json())
      .then(data => {
        this.countries = data
        this.initMap()
        this.setGeoJsonLayer()
        this.initInfo()
      })
      .catch(error => console.error('Error fetching JSON:', error));
  }
}

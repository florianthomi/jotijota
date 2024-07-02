import { Controller } from "@hotwired/stimulus";
import {Tooltip} from 'chart.js';
import {ChoroplethChart, topojson} from 'chartjs-chart-geo'

ChoroplethChart.register(Tooltip)

export default class extends Controller {
  static targets = [ "canvas" ]
  static values = {
    url: String
  }

  connect() {
    const jsonUrl = this.urlValue;
    const canvas = this.canvasTarget
    fetch(jsonUrl)
      .then(response => response.json())
      .then(data => {
        const countries = topojson.feature(data, data.objects.countries1).features;

        new ChoroplethChart(canvas.getContext("2d"), {
          data: {
            labels: countries.map((d) => d.properties.name),
            datasets: [{
              label: 'Countries',
              data: countries.map((d) => ({feature: d, value: 0})),
            }]
          },
          options: {
            showOutline: true,
            showGraticule: true,
            plugins: {
              legend: {
                display: false
              },
            },
            scales: {
              color: {
                axis: 'x',
                interpolate: "buGn",
                quantize: 0,
                beginAtZero: true,
                ticks: {
                  stepSize: 20
                }
              },
              projection: {
                axis: 'x',
                projection: 'naturalEarth1'
              }
            }
          }
        });
      })
      .catch(error => console.error('Error fetching JSON:', error));
  }
}

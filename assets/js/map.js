import 'chart.js/auto';
import {ChoroplethChart, topojson} from 'chartjs-chart-geo'
import '../styles/map.scss'

const data = require('./world-countries.json');

const canvas = document.getElementById("canvas")

if (canvas) {
    const countries = topojson.feature(data, data.objects.countries1).features;

    const chart = new ChoroplethChart(canvas.getContext("2d"), {
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
            interpolate: "greens",
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          },
          xy: {
            projection: 'naturalEarth1',
          }
        }
      }
    });

    canvas.addEventListener('refresh', (e) => {
      fetch(canvas.dataset.url).then((r) => r.json()).then((data) => {
        chart.data.datasets[0].data = countries.map((d) => ({feature: d, value: data[d.properties['Alpha-2']] || 0}))
        chart.update()
      })
    })
    canvas.dispatchEvent(new Event('refresh'))
}

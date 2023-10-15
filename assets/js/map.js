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
            interpolate: "buGn",
            quantize: 0,
            beginAtZero: true,
            ticks: {
              stepSize: 20
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
        const total = Object.values(data).reduce((acc, value) => {
          acc.jid += value;
          acc.countries = value > 0 ? acc.countries + 1 : acc.countries;
          return acc
        }, {jid: 0, countries: 0})
        document.getElementById('nbre_jid').innerText = total.jid;
        document.getElementById('nbre_pays').innerText = total.countries;
        chart.update()
      })
    })
    canvas.dispatchEvent(new Event('refresh'))
}

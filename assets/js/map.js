import 'chart.js/auto';
import {ChoroplethChart, topojson} from 'chartjs-chart-geo'
import '../styles/map.scss'

const canvas = document.getElementById("canvas")

if (canvas) {
  fetch('https://raw.githubusercontent.com/deldersveld/topojson/master/world-countries.json').then((r) => r.json()).then((data) => {
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
            min: 0
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
  });
}

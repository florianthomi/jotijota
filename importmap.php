<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.4',
    ],
    '@symfony/ux-live-component' => [
        'path' => './vendor/symfony/ux-live-component/assets/dist/live_controller.js',
    ],
    'chart.js' => [
        'version' => '4.4.3',
    ],
    '@kurkle/color' => [
        'version' => '0.3.2',
    ],
    'chartjs-chart-geo' => [
        'version' => '4.3.1',
    ],
    'd3-geo' => [
        'version' => '3.1.1',
    ],
    'chart.js/helpers' => [
        'version' => '4.4.3',
    ],
    'd3-scale-chromatic' => [
        'version' => '3.1.0',
    ],
    'topojson-client' => [
        'version' => '3.1.0',
    ],
    'd3-array' => [
        'version' => '3.2.4',
    ],
    'd3-interpolate' => [
        'version' => '3.0.1',
    ],
    'd3-color' => [
        'version' => '3.1.0',
    ],
    'internmap' => [
        'version' => '2.0.3',
    ],
    'maska' => [
        'version' => '3.0.0-beta4',
    ],
];

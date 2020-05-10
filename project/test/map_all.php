<?php
//code from https://codepen.io/ronaldaug/pen/dQwNJp
require '../system/database.php';

$st = $connection->prepare('SELECT coords_lat, coords_long FROM records');
$st->execute();

$count = $st->rowCount();
$row = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/openlayers/4.6.5/ol.css" />
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/openlayers/4.6.5/ol.js"></script>
<script>
    var mandalay = ol.proj.fromLonLat([96.0891, 21.9588]);
    var view = new ol.View({
        center: mandalay,
        zoom: 6// 5
    });
    var places = [<?php
$c = 0;
foreach ($row as $result) {
    $c++;
    echo "[" . $result['coords_lat'] . ',' . $result['coords_long'] . ',' . '\'http://maps.google.com/mapfiles/ms/micons/blue.png\']';
    if ($c != $count) {
        echo ',';
    }
}
?>
    ];

    var features = [];
    for (var i = 0; i < places.length; i++) {
        var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([places[i][0], places[i][1]], 'EPSG:4326', 'EPSG:3857')),
        });


        var iconStyle = new ol.style.Style({
            image: new ol.style.Icon({
                src: places[i][2],
                color: places[i][3],
                crossOrigin: 'anonymous',
            })
        });
        iconFeature.setStyle(iconStyle);
        vectorSource.addFeature(iconFeature);
    }



    var vectorLayer = new ol.layer.Vector({
        source: vectorSource,
        updateWhileAnimating: true,
        updateWhileInteracting: true
    });

    var map = new ol.Map({
        target: 'map',
        view: view,
        layers: [
            new ol.layer.Tile({
                preload: 3,
                source: new ol.source.OSM(),
            }),
            vectorLayer,
        ],
        loadTilesWhileAnimating: true,
    });
</script>
<style>
    /* Always set the map height explicitly to define the size of the div
   * element that contains the map. */
    #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }  
</style>
<div id="map" class="map"></div>
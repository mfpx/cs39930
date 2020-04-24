<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="js/logout.js"></script>
<script type="text/javascript" src="//openlayers.org/api/OpenLayers.js"></script>
<?php
is_loggedin(1);
require './system/database.php';

$img_name = "image1";
try {
    $st = $connection->prepare('SELECT * FROM records WHERE img_name = :img_name');
    $st->bindValue(':img_name', $img_name, PDO::PARAM_STR);
    $st->execute();

    $row = $st->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $st = $connection->prepare('SELECT * FROM images WHERE img_name = :img_name');
    $st->bindValue(':img_name', $img_name, PDO::PARAM_STR);
    $st->execute();

    $img_row = $st->fetch(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    echo "Error: " . $e->getMessage();
}

//var_dump($row);
if (!empty($img_row['image'])) {
    echo '<img height="240" width="427" src="data:image/jpeg;base64,' . base64_encode($img_row['image']) . '"/>';
    echo '<div id="mapdiv"></div>';
}
?>
<script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat(<?php echo $row['coords_long'] . ',' . $row['coords_lat'] ?>)
            .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                    );

    var zoom = 6;

    var markers = new OpenLayers.Layer.Markers("Markers");
    map.addLayer(markers);

    markers.addMarker(new OpenLayers.Marker(lonLat));

    map.setCenter(lonLat, zoom);
</script>
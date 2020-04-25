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
    
    if($row['sex'] == 1){
        $sex = $male;
    } else {
        $sex = $female;
    }
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

?>
<div class="info-container">
    <div class="intro-text">
        <p>
            <?php echo $image_name . ': ' . $row['img_name'] ?> <br />
            <?php echo $species . ': ' . $row['species'] ?> <br />
            <?php echo $sex_text . ': ' . $sex ?> <br />
            <?php echo $abdomen_length . ': ' . $row['abdomen_length'] ?> <br />
            <?php echo $abdomen_width . ': ' . $row['abdomen_width'] ?> <br />
            <?php echo $ship_name . ': ' . $row['boat'] ?><br />
            <?php echo $latitude . ': ' . $row['coords_lat'] . ' ' . $cardinals_north ?><br />
            <?php echo $longitude . ': ' . $row['coords_long'] . ' ' . $cardinals_west ?><br />
        </p>
        <div id="mapdiv"></div>
        <div class="buttons">
            <button class="back">Back</button>
            <button class="back">Back</button>
            <button class="back">Back</button>
        </div>
    </div>
    <?php
    if (!empty($img_row['image'])) {
        echo '<div class = "references">
        <img src="data:image/jpeg;base64,' . base64_encode($img_row['image']) . '" />
        </div>';
    }
    ?>
</div>
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
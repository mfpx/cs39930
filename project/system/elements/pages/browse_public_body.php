<?php
require './system/database.php';

$image = filter_input(INPUT_GET, 'image', FILTER_SANITIZE_STRING);

try {
    $st_chk = $connection->prepare('SELECT img_name FROM records WHERE img_name = :image');
    $st_chk->bindValue(':image', $image, PDO::PARAM_STR);
    $st_chk->execute();

    $result = $st_chk->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

if (!empty($image) && $result['img_name'] == $image) {
    $img_name = $image;
} else {
    redirect('list_public.php', 301); //Redirect if no image provided
}
try {
    $st_data = $connection->prepare('SELECT * FROM records WHERE img_name = :img_name');
    $st_data->bindValue(':img_name', $img_name, PDO::PARAM_STR);
    $st_data->execute();

    $row = $st_data->fetch(PDO::FETCH_ASSOC);

    if ($row['sex'] == 1) {
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

$connection = null;
?>
<div class="info-container">
    <div class="intro-text">
        <p>
            <?php
            echo $image_name . ': ' . $row['img_name'] . '<br />';
            echo $species . ': ' . $row['species'] . '<br />';
            echo $sex_text . ': ' . $sex . '<br />';
            if (!empty($row['carapace_width'])) {
                echo $carapace_width . ': ' . $row['carapace_width'] . '<br />';
            }
            if (!empty($row['abdomen_length'])) {
                echo $abdomen_length . ': ' . $row['abdomen_length'] . '<br />';
            }
            if (!empty($row['abdomen_width'])) {
                echo $abdomen_width . ': ' . $row['abdomen_width'] . '<br />';
            }
            echo $ship_name . ': ' . $row['boat'] . '<br />';
            ?>
        </p>
        <div id="mapdiv"></div>
        <div class="buttons">
            <button class="browse-nav" onclick="document.location = 'list_public.php'">Back</button>
            <!--<button class="back">Back</button>-->
        </div>
    </div>
    <?php
    if (!empty($img_row['image'])) {
        echo '<div class = "references">
        <img src="data:image/jpeg;base64,' . base64_encode($img_row['image']) . '" />
        </div>';
    } else {
        echo '<div class = "references">
        <img src="media/empty.jpg" />
        </div>';
    }
    ?>
</div>
</body>
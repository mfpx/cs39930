<?php
require 'system/database.php';

try {
    $st_dat = $connection->prepare('SELECT * FROM records');
    $st_dat->execute();

    $result = $st_dat->fetchAll(PDO::FETCH_ASSOC);
    $count = $st_dat->rowCount();
} catch (Exception $ex) {
    
}
?>

<!-- Uncomment this block if there's enough time to make a search -->
<!--<hr class="override" />
<form action="#" class="specs">
    <label for="name">Name:</label>
    <input type="text" id="name" />
    <label for="male">Male</label>
    <input type="radio" id="male" name="sex" selected />
    <label for="female">Female</label>
    <input type="radio" id="female" name="sex" />
    <label for="species"></label>
    <select name="species" id="species">
        <option value="species" selected disabled hidden>Species</option>
        <option value="lobster">Lobster</option>
        <option value="crab">Crab</option>
    </select>
    <label for="boat-name">Boat name:</label>
    <input type="text" id="boat-name" />
    <button type="submit" id="search">Search</button>
</form>-->
<div class="gallery">
    <?php
    if ($count >= 1) {
        foreach ($result as $record) {

            try {
                $st = $connection->prepare('SELECT image FROM images WHERE img_name = :img');
                $st->bindValue(':img', $record['img_name'], PDO::PARAM_STR);
                $st->execute();

                $row = $st->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }

            echo '
            <div class="desc">
            <a href="browse_public.php?image=' . $record['img_name'] . '">
            ';
            
            if (!empty($row['image'])) {
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" />';
            } else {
                echo '<img src="media/empty.jpg" />';
            }
            
            if($record['sex'] == 1){
                $sex = 'Male';
            } else {
                $sex = 'Female';
            }
            
            echo '
            <br />        
            ' . $record['img_name'] . ', ' . $record['species'] . ', ' . $sex . '
            </a>
            </div>
        ';
        }
    } else {
        echo 'No records found!';
    }

    $connection = null;
    ?>
</div>
<?php

require 'system/database.php';
require 'system/security.php';

if (isset($_POST['function']) && $_POST['function'] == "delete_record" && csrf_verify($_POST['token'])) {
    $img_name = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);

    try {
        $connection->beginTransaction();

        $st_rec_del = $connection->prepare('DELETE FROM records WHERE img_name = :img');
        $st_rec_del->bindValue(':img', $img_name, PDO::PARAM_STR);

        $st_img_del = $connection->prepare('DELETE FROM images WHERE img_name = :img');
        $st_img_del->bindValue(':img', $img_name, PDO::PARAM_STR);

        $st_rec_del->execute();
        $st_img_del->execute();

        if ($connection->commit()) {
            echo 1;
        } else {
            echo 0;
        }
    } catch (Exception $e) {
        $connection->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

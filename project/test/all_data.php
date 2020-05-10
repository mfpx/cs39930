<?php

require '../system/database.php';

$st = $connection->prepare('SELECT * FROM records');
$st->execute();

$row = $st->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($row);
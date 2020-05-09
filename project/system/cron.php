<?php

require 'database.php';

try {
    $st = $connection->prepare('DELETE FROM pass_resets WHERE date < NOW() - INTERVAL 30 DAY');
    $st->execute();
} catch (PDOException $e) {
    $time = date("H:i:s");
    $date = date("d/m/Y");

    if (file_exists("log/cron_log")) {
        $log = fopen("log/cron_log", "a") or die("Unable to append to file!");
    } else {
        $log = fopen("log/cron_log", "w") or die("Unable to write file!");
    }
    $txt = $date . " " . $time . ":" . "Error: " . $e->getMessage() . "\n";
    fwrite($log, $txt);
    fclose($log);
}

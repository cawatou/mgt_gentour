<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once 'Threads.php';
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/cron/start.txt', 'start', FILE_APPEND);
for($i = 1; $i <= 20; $i++){
    $Thread->Create(function() use ($i){
        sleep(1);
        tt($i);
        //$file = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dev/cron/rid.txt');
        echo $file."<br>";
    });
}
$start = microtime(true);
$response = $Thread->Run();
$end = microtime(true);
echo '<hr /> OK ';
echo count($response);
echo ' from 20';
echo "<hr /> Script execution time: ".($end-$start)." sec.";

function tt($i){
    echo "fdasfasfas";
    //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/cron/thread.txt', "thread - ".$i."\r\n", FILE_APPEND);
}
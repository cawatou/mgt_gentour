<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
phpinfo();

if (! function_exists('pcntl_fork')) die('PCNTL functions not available on this PHP installation');


$pid = pcntl_fork();
if($pid == 0){
    make_her_happy();
}elseif($pid > 0){
    $pid2 = pcntl_fork();
    if($pid2 == 0){
        find_another_one();
    }
}

function make_her_happy(){
    echo "make_her_happy";
}

function find_another_one(){
    echo "find_another_one";
}
?>



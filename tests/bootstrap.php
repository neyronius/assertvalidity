<?php

$loader = @include __DIR__ . '/../vendor/autoload.php';

if(!$loader){
    die("Can't found dependencies");
}
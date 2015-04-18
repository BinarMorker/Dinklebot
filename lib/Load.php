<?php

// RESQUE
include_once "lib/Resque/Resque.php";
foreach (glob("lib/Resque/Resque/*.php") as $filename) {
    include_once $filename;
}
foreach (glob("lib/Resque/Resque/Job/*.php") as $filename) {
    include_once $filename;
}
foreach (glob("lib/Resque/Resque/Failure/*.php") as $filename) {
    include_once $filename;
}

// CREDIS
foreach (glob("lib/Credis/*.php") as $filename) {
    include_once $filename;
}

// PSR/LOG
include_once "lib/Psr/Log/LoggerInterface.php";
include_once "lib/Psr/Log/LoggerAwareInterface.php";
foreach (glob("lib/Psr/Log/*.php") as $filename) {
    include_once $filename;
}
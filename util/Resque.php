<?php
include_once "lib/Resque.php";

foreach (glob("lib/Resque/*.php") as $filename) {
    include_once $filename;
}

foreach (glob("lib/Resque/Job/*.php") as $filename) {
    include_once $filename;
}

foreach (glob("lib/Resque/Failure/*.php") as $filename) {
    include_once $filename;
}
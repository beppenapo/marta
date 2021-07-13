<?php
session_start();
require 'vendor/autoload.php';
use \Marta\Home;
$obj = new Home();
$stat = $obj->statHome();
print_r($stat);
?>

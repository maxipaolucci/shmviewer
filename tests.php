<?php
/* this file is for tests */
$path = '.';
include("./includes/ErrorHandler.class.php");
include("./model.php");

set_time_limit(1000);

//$query = "SELECT vaca FROM animales";
//PostTable::getInstance()->executeQuery($query);

$query = "delete from popo";
PostTable::getInstance()->executeQuery($query);
?>
<?php

require_once "mysql.class.php";
$archivo = dirname((__FILE__))."/config.inc.php";
$mysql= new mysql($archivo);
?>
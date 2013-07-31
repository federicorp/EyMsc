<?php

function __autoload($clase){
	echo $clase;
}

$archivo = dirname((__FILE__))."/config.inc.php";
$mysql = new mysql($archivo);

?>
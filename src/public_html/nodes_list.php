<?php 
	require_once '../moto/config/motodConfig.php';
	require_once '../moto/classes/MotoRPC.php';
	
	$nodes = MotoRPC::getpeerinfo();
	
	foreach ($nodes as $node) {
		echo $node['addr'].'<br>';		
	}
?>

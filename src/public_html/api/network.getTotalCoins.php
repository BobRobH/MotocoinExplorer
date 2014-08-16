<?php
	require_once '../../moto/config/motodConfig.php';
	require_once '../../moto/classes/MotoRPC.php';
	require_once '../../moto/classes/MotoHelpers.php';
	
	$network_info = MotoRPC::getinfo();
	$network_coins = MotoHelpers::getcoinsvolume($network_info["blocks"]);
	
	echo $network_coins;
?>
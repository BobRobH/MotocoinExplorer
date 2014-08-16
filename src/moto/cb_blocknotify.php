<?php 
	/**
	 * Callback script for `motocoind -blocknotify`
	 */
	require_once 'config/motodConfig.php';
	require_once 'classes/MotoRPC.php';
	require_once 'classes/MotoHelpers.php';
	
	$db = new SQLite3(__DIR__.'/../sqlite3_db/motodb.db');
	$res_a = $db->query('SELECT MAX(block_id) FROM `transaction`')->fetchArray();
	$db_height = $res_a[0];
	$db_height = $db_height != NULL ? $db_height : -1; 
	$network_info = MotoRPC::getinfo();
	$motod_height = $network_info["blocks"];
	

	for ($i=$db_height+1; $i<=$motod_height; $i++) {
		$block_hash = MotoRPC::getblockhash($i);
		$raw_block = MotoRPC::getblock($block_hash);
		foreach ($raw_block["tx"] as $index => $tx) {
			$raw_tx = MotoRPC::getrawtransaction($tx);
			$db->query("
				INSERT 
					INTO `transaction`(block_id, `transaction`, `date`, moto_explorer_date) 
					VALUES({$i}, '{$tx}', datetime('{$raw_tx["time"]}', 'unixepoch'), datetime('now', 'utc'))
			");
			$tx_id = $db->lastInsertRowID();
			if (isset($raw_tx["vin"])) {
				foreach ($raw_tx["vin"] as $key => $txin) {
					if (isset($txin["txid"])) {
						$parent_tx = MotoRPC::getrawtransaction($txin["txid"]);
						$parent_tx_time = $parent_tx['time'];
						$parent_tx_value = $parent_tx["vout"][$txin["vout"]]['value'];
						$parent_addresses = $parent_tx["vout"][$txin["vout"]]["scriptPubKey"]["addresses"];
						
						foreach ($parent_addresses as $address) {
// 							$db->query(
// 								'INSERT
// 									INTO address_transaction(height, address, `transaction`, `date`, `type`, `amount`)
// 									VALUES('.$i.', "'.$address.'", "'.$tx.'", datetime('.$parent_tx_time.', "unixepoch"), 1, '.$parent_tx_value.')'
// 							);
							$db->query("INSERT OR IGNORE INTO address(address) VALUES('{$address}')");
							
							$db->query("
								INSERT INTO address_has_transaction(address_id, transaction_id, transaction_type, coins_count)
									VALUES(
										(SELECT address_id FROM address WHERE address = '{$address}'),
										{$tx_id},
										-1,
										{$parent_tx_value}
									)
							");
						}
					}
				}
			}
			if (isset ($raw_tx["vout"])) {
				foreach ($raw_tx["vout"] as $key => $txout) {
					if (isset ($txout["scriptPubKey"]["addresses"])) {
						foreach ($txout["scriptPubKey"]["addresses"] as $key => $address) {
							//echo $i.' '.$tx.' '.$address.PHP_EOL;
// 							$db->query(
// 								'INSERT 
// 									INTO address_transaction(height, address, `transaction`, `date`, `type`, `amount`) 
// 									VALUES('.$i.', "'.$address.'", "'.$tx.'", datetime('.$raw_tx["time"].', "unixepoch"), 0, '.$txout['value'].')'
// 							);
							$db->query("INSERT OR IGNORE INTO address(address)	VALUES('{$address}')");
							
							$db->query("
								INSERT INTO address_has_transaction(address_id, transaction_id, transaction_type, coins_count)
									VALUES(
										(SELECT address_id FROM address WHERE address = '{$address}'),
										{$tx_id},
										1,
										{$txout['value']}
									)
							");
						}	
					}	
				}
			}
		}
	}
?>
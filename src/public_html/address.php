<?php
	$address = isset($_REQUEST['address']) ? $_REQUEST['address'] : die('Empty query parameters: address');
	$db = new SQLite3(__DIR__.'/../sqlite3_db/motodb.db', SQLITE3_OPEN_READONLY);
	
	$current_address = $db->query('SELECT * FROM address WHERE address="'.SQLite3::escapeString($address).'"')->fetchArray(SQLITE3_ASSOC);
	
	if (empty($current_address)) {
		die('Address not found');
	}

	$result = $db->query('
		SELECT
			aht.rowid as aht_id,
            t.`transaction`,
			t.`date`,      
            aht.transaction_id,
            aht.coins_count, 
            aht.transaction_type,
            a_i.address as transaction2_address,            
            aht2.transaction_type as transaction2_type,
            aht2.coins_count as transaction2_coins

			FROM address_has_transaction aht, `transaction` t
        		LEFT JOIN address_has_transaction aht2 ON(aht.transaction_id = aht2.transaction_id)        
				LEFT JOIN address a_i ON(aht2.address_id = a_i.address_id)
			WHERE
				aht.address_id = '.$current_address['address_id'].'
        		AND aht.transaction_id = t.transaction_id
			ORDER BY
				t.`date` DESC, aht2.transaction_type
	');
	
	$transactions = array();
	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		$t_index = $row['aht_id'];
		if (!isset($transactions[$t_index])) {
			$transactions[$t_index] = array(
				'transaction' => $row['transaction'],
				'type' => $row['transaction_type'],
				'date' => $row['date'],
				'coins_count' => $row['coins_count'],
				'addresses' => array()
			);
		}
		if (!empty($row['transaction2_address'])) {
			$transactions[$t_index]['addresses'][] = $row;
		}
	}
?>

<?php include 'parts/parts.header.php';?>
<div class="cl-sm-12">
	<table class="table table-bordered">
		<tr>
			<td>Address
			<td><?=htmlspecialchars($address)?>
		<tr>
			<td>Current coins amount
			<td><?=$current_address['coins_count']?>
	</table>	
</div>
<div class="cl-sm-12">
	<table class="table" >
		<?php foreach ($transactions as $t) { ?>
			
				<?php if ($t['type']==1) { ?>
				<tr class="bg-success">
					<td colspan="1">
						<span title="coins sent to the current address"><?=$t['coins_count']?></span>
					<td colspan="5">
						<span class="glyphicon glyphicon-arrow-right" title="Input transaction"></span>
						&nbsp;
						<a href="transaction.php?transaction=<?=$t['transaction']?>"><?=$t['transaction']?></a>
					<td>
				<?php } else { ?>
				<tr class="bg-warning">
					<td>
					<td colspan="5">
						<a href="transaction.php?transaction=<?=$t['transaction']?>"><?=$t['transaction']?></a>
						&nbsp;
						<span class="glyphicon glyphicon-arrow-right" title="Output transaction"></span>
					<td>
						<span class="text-danger" title="coins sent from the current address"><?=$t['coins_count']?></span>
				<?php } ?>
				<td>
					&nbsp;
					<span class="text-muted"><small><?=$t['date']?></small></span>
			<?php 
				$i = 1; 
				foreach ($t['addresses'] as $a) {
			?>
				<tr>
					<td style="width: 50px;">
					<?php if ($a['transaction2_type']==1) { ?>
						
						<td>
							<small><span class="text-muted" title="coins sent to the current address"><?=$a['transaction2_coins']?></span></small>
						<td>
							<small><span class="text-muted glyphicon glyphicon-arrow-right" title="Input transaction"></span></small>
						<td class="text-muted"><small><?=$i++?></small>
						<td>
							<?php if ($a['transaction2_address'] == $address) { ?>
								<?=$a['transaction2_address']?>
							<?php } else { ?>
								<a href="./address.php?address=<?=$a['transaction2_address']?>"><?=$a['transaction2_address']?></a>
							<?php } ?>
						<td>
						<td>
					<?php } else { ?>
						<td>
						<td>
						<td class="text-muted"><small><?=$i++?></small>
						<td>
							<?php if ($a['transaction2_address'] == $address) { ?>
								<?=$a['transaction2_address']?>
							<?php } else { ?>
								<a href="./address.php?address=<?=$a['transaction2_address']?>"><?=$a['transaction2_address']?></a>
							<?php } ?>
						<td>
							<small><span class="text-muted glyphicon glyphicon-arrow-right" title="Output transaction"></span></small>
						<td>
							<small><span class="text-muted" title="coins sent from the current address"><?=$a['transaction2_coins']?></span></small>
					<?php } ?>
						<td>
			<?php } ?>
		<?php } ?>			
	</table>	
</div>
<?php include 'parts/parts.footer.php';?>
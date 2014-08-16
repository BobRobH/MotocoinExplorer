<?php
	require_once '../moto/config/motodConfig.php';
	require_once '../moto/classes/MotoRPC.php';

	$tx_id = isset($_REQUEST['transaction']) ? $_REQUEST['transaction'] : die('Empty query parameters: transaction');
	$raw_tx = MotoRPC::getrawtransaction($tx_id);
?>
<?php include 'parts/parts.header.php';?>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered">
				<tr>
	            	<td>Transaction
	                <td><?=$raw_tx["txid"]?>		
				<tr>
	            	<td>TX Version
	                <td><?=$raw_tx["version"]?>
	            <tr>
	                <td>TX Time
	                <td><?=date ("F j, Y, H:i:s", $raw_tx["time"])?>
	            <tr>
	                <td>Lock Time
	                <td><?=$raw_tx["locktime"]?>
	            <tr>
	                <td>Confirmations
	                <td><?=$raw_tx["confirmations"]?>
	            <tr>
	                <td>Block Hash
	                <td><a href="./block.php?block_hash=<?=$raw_tx["blockhash"]?>"><?=$raw_tx["blockhash"]?></a> 
	            <tr>
	                <td>Hex data
	                <td><?=wordwrap($raw_tx["hex"], 75, "<br>", TRUE)?>                             
		</table>	
	</div>
</div>

	<?php foreach ($raw_tx["vin"] as $key => $txin) { ?>
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered">
				<thead>
					<tr><th colspan="2">Input Transaction #<?=$key?>
				</thead>
				<tbody>
					<?php if (isset ($txin["coinbase"])) { ?>
						<tr>
							<td>Coinbase
							<td><?=$txin["coinbase"]?>
						<tr>
							<td>Sequence
							<td><?=$txin["sequence"]?>				
					<?php } else { ?>
						<tr>
							<td>TX ID
							<td><a href="transaction.php?transaction=<?=$txin["txid"]?>"><?=$txin["txid"]?></a>
						<tr>
							<td>TX Output
							<td><?=$txin["vout"]?>
						<tr>
							<td>TX Sequence
							<td><?=$txin["sequence"]?>
						<tr>
							<td>Script Sig (ASM)
							<td><?=wordwrap($txin["scriptSig"]["asm"], 75, "<br>", true)?>
						<tr>
							<td>Script Sig (HEX)
							<td><?=wordwrap($txin["scriptSig"]["hex"], 75, "<br>", true)?>
																			
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php } ?>
	
	<?php foreach ($raw_tx["vout"] as $key => $txout) { ?>
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered">
				<thead>
					<tr><th colspan="2">Output Transaction #<?=$key?>
				</thead>
				<tbody>
					<tr>
						<td>TX Value
						<td><?=$txout["value"]?>
					<tr>
						<td>TX Type
						<td><?=$txout["scriptPubKey"]["type"]?>
					<tr>
						<td>Required Sigs
						<td><?=$txout["scriptPubKey"]["reqSigs"]?>
					<tr>
						<td>Script Pub Key (ASM)
						<td><?=$txout["scriptPubKey"]["asm"]?>
					<tr>
						<td>Script Pub Key (HEX)
						<td><?=$txout["scriptPubKey"]["hex"]?>
			<?php if (isset ($txout["scriptPubKey"]["addresses"])) { 
				foreach ($txout["scriptPubKey"]["addresses"] as $key => $address); { ?>
					<tr>
						<td>Address #<?=$key?>
						<td><a href="./address.php?address=<?=$address?>"><?=$address?></a>
			<?php }} ?>						
				</tbody>
			</table>
		</div>
	</div>
	<?php } ?>	
 <?php include 'parts/parts.footer.php';?>
<?php 
	require_once '../moto/config/motodConfig.php';
	require_once '../moto/classes/MotoRPC.php';

	$block_id = isset($_REQUEST['block_id']) ? $_REQUEST['block_id'] : null;
	
	$network_info = MotoRPC::getinfo();
	$blocks_count = $network_info["blocks"];
	
	if ($block_id == null) {
		$block_id = $blocks_count;
	}

	$transactions = array();
	for ($i=$block_id; $i>=0 && $i>$block_id-10; $i--) {
		$block_hash = MotoRPC::getblockhash(intval ($i));
		$raw_block = MotoRPC::getblock($block_hash);
		foreach ($raw_block["tx"] as $tx) {
			$transactions[] = array("block_id"=>$i, "block_hash"=>$raw_block["hash"], "tx"=>$tx);
		}
	}
?>
<?php include 'parts/parts.header.php';?>
	<div class="cl-sm-12">
		<table class="table table-bordered">
			<thead>
				<tr><th colspan="3">Transactions in blocks #<?=$block_id-9?> - <?=$block_id?> / <?=$blocks_count?>
			</thead>
			<tbody>
				<tr>
					<td>Block #
					<td>Block hash
					<td>Transaction
				<?php foreach ($transactions as $t) { ?>
					<tr>
						<td><?=$t["block_id"]?>
						<td><a href="./block.php?block_hash=<?=$t["block_hash"]?>"><?=$t["block_hash"]?></a>
						<td><a href="./transaction.php?transaction=<?=$t["tx"]?>"><?=$t["tx"]?></a>
				<?php } ?>
			</tbody>
		</table>
	</div>
<div class="row">
	<div class="col-sm-6">
		<?php if ($block_id-10>=0) {?>
			<a href="./transactions.php?block_id=<?=$block_id-10?>"><span class="glyphicon glyphicon-step-backward"></span>Previous Transactions</a>
		<?php }?>
	</div>
	<div class="col-sm-6">
		<?php if ($block_id<$blocks_count) {?>
			<a class="pull-right" href="./transactions.php?block_id=<?=$block_id+1?>">Next Transactions<span class="glyphicon glyphicon-step-forward"></span></a>
		<?php }?>
	</div>
</div>
<?php include 'parts/parts.footer.php';?>
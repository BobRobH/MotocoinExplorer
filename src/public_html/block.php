<?php 
	require_once '../moto/config/motodConfig.php';
	require_once '../moto/classes/MotoRPC.php';

	$block_id = isset($_REQUEST['block_id']) ? $_REQUEST['block_id'] : -1;
	$block_hash = isset($_REQUEST['block_hash']) ? $_REQUEST['block_hash'] : null;
	
	if ($block_hash) {
		$raw_block = MotoRPC::getblock ($block_hash);
	}
	else 
	if ($block_id>=0) {	
		$block_hash = MotoRPC::getblockhash(intval ($block_id));
		$raw_block = MotoRPC::getblock($block_hash);
	}
	else {
		die('Empty query parameters: block_id & block_hash');
	}
?>

<?php include 'parts/parts.header.php';?>
<div class="row">
	<div class="col-sm-6">
		<table class="table table-bordered">
			<tr>
            	<td>Block Height
                <td><?=$raw_block["height"]?>
            <tr>
                <td>Block Time
                <td><?=date ("F j, Y, H:i:s", $raw_block["time"])?>
            <tr>
                <td>Block Version
                <td><?=$raw_block["version"]?>
            <tr>
                <td>Nonce
                <td><?=$raw_block["nonce"]?>
		</table>
	</div>
	<div class="col-sm-6">
		<table class="table table-bordered">
            <tr>
                <td>Block Size
                <td><?=$raw_block["size"]?>
			<tr>
            	<td># of Confirmations
                <td><?=$raw_block["confirmations"]?>
            <tr>
                <td>Target time
                <td><?=hexdec($raw_block["bits"])/250?>
			<tr>
                <td>Frames
                <td><?=$raw_block["frames"]?>
		</table>
	</div>	
</div>
<div class="cl-sm-12">
	<table class="table table-bordered">
		<tr>
			<td>Merkle Root
			<td><?=$raw_block["merkleroot"]?>
		<tr>
			<td>Block Hash
			<td><?=$raw_block["hash"]?>
		<tr>
			<td>Inputs
			<td><?=wordwrap(implode(" ", $raw_block["inputs"]), 75, "<br>", TRUE)?>
	</table>	
</div>
<div class="cl-sm-12">
	<table class="table table-bordered">
			<tr><td>Transactions In This Block
		<?php foreach ($raw_block["tx"] as $index => $tx) { ?>
			<tr><td><a href="transaction.php?transaction=<?=$tx?>"><?=$tx?></a>
		<?php } ?>			
	</table>	
</div>
<div class="row">
	<div class="col-sm-6">
		<?php if (!empty($raw_block["previousblockhash"])) {?>
			<a href="./block.php?block_hash=<?=$raw_block["previousblockhash"]?>"><span class="glyphicon glyphicon-step-backward"></span>Previous Block</a>
		<?php }?>
	</div>
	<div class="col-sm-6">
		<?php if (!empty($raw_block["nextblockhash"])) {?>
			<a class="pull-right" href="./block.php?block_hash=<?=$raw_block["nextblockhash"]?>">Next Block<span class="glyphicon glyphicon-step-forward"></span></a>
		<?php }?>
	</div>		
</div>
<?php include 'parts/parts.footer.php';?>
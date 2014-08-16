<?php
	require_once '../moto/config/motodConfig.php';
	require_once '../moto/classes/MotoRPC.php';
	require_once '../moto/classes/MotoHelpers.php';
	
	$network_info = MotoRPC::getinfo();
	$network_coins = MotoHelpers::getcoinsvolume($network_info["blocks"]);
?>

<?php include 'parts/parts.header.php';?>
<div class="well">
			<div class="row">
				<div class="col-sm-6">
					<h4>Network info</h4>
					<p>Blocks: <?=$network_info["blocks"]?></p>
					<p>Coins: <?=$network_coins?></p>
					<p>Connections: <?=$network_info["connections"]?></p>
				</div>
				<div class="col-sm-6">
	    			<form method="GET" action="./block.php" class="form-group input-group">
	      				<input type="text" name="block_id" class="form-control" placeholder="Block number">
		      			<span class="input-group-btn">
		        			<input class="btn btn-default" type="submit" value="Go to block"/>
		      			</span>
	      			</form>
	      			<form method="GET" action="./block.php" class="form-group input-group">
		      			<input type="text" name="block_hash" class="form-control" placeholder="Block hash">
		      			<span class="input-group-btn">
		      				<input class="btn btn-default" type="submit" value="Go to block"/>
		      			</span>
		      		</form>
	      			<form method="GET" action="./transaction.php" class="form-group input-group">
		      			<input type="text" name="transaction" class="form-control" placeholder="Transaction">
		      			<span class="input-group-btn">
		      				<input class="btn btn-default" type="submit" value="Go to transaction"/>
		      			</span>
		      		</form>
	      			<form method="GET" action="./address.php" class="form-group input-group">
		      			<input type="text" name="address" class="form-control" placeholder="Address">
		      			<span class="input-group-btn">
		      				<input class="btn btn-default" type="submit" value="Go to address"/>
		      			</span>
		      		</form>		      		
				</div>
			</div>
</div>
<?php include 'parts/parts.footer.php';?>


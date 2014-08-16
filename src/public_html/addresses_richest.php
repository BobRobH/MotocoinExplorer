<?php
	$db = new SQLite3(__DIR__.'/../sqlite3_db/motodb.db', SQLITE3_OPEN_READONLY);

	$result = $db->query('SELECT * FROM address ORDER BY coins_count DESC LIMIT 100');
?>

<?php include 'parts/parts.header.php';?>
<div class="cl-sm-12">
	<table class="table" >
		<tr>
			<th>#
			<th>Address
			<th>Coins
		<?php $i=1; while($row = $result->fetchArray(SQLITE3_ASSOC)) { ?>
			<tr>
				<td class="text-muted"><small><?=$i++?></small>
				<td><a href="./address.php?address=<?=$row['address']?>"><?=$row['address']?></a>
				<td><?=$row['coins_count']?>
		<?php } ?>			
	</table>	
</div>
<?php include 'parts/parts.footer.php';?>
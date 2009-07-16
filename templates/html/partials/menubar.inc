<div class="menuBar">
	<form method="get" action="<?php echo BASE_URL; ?>/addresses">
		<fieldset><legend>Find an address</legend>
			<label>
				Search Addresses
				<input name="address" value="<?php echo isset($_GET['address']) ? View::escape($_GET['address']) : ''; ?>" />
			</label>
			<button type="submit" class="search">Search</button>
		</fieldset>
	</form>
	<ul><li><a href="<?php echo BASE_URL; ?>/streets">Search Streets</a></li>
		<li><a href="<?php echo BASE_URL; ?>/plats">Search Plats</a></li>
	</ul>
</div>

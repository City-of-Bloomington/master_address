<div id="menuBar">
	<form method="get" action="<?php echo BASE_URL; ?>/streets">
		<fieldset><legend>Find a street</legend>
			<label>
				Search Streets
				<input name="address" value="<?php echo isset($_GET['address']) ? View::escape($_GET['address']) : ''; ?>" />
			</label>
			<button type="submit" class="search">Search</button>
		</fieldset>
	</form>
	<ul><li><a href="<?php echo BASE_URL; ?>">Home</a></li>
		<li><a href="<?php echo BASE_URL; ?>/plats">Search Plats</a></li>
		<li><a href="<?php echo BASE_URL; ?>/contacts">Contacts</a></li>
		<li>Activity Report</li>
	</ul>
</div>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<form method="get" action="<?php echo BASE_URL; ?>/locations/home.php">
	<fieldset><legend>Street Address</legend>
		<div>
			<label for="address">Street Address</label>
			<input name="address" id="address"
					value="<?php echo isset($_GET['address']) ? View::escape($_GET['address']) : ''; ?>" />
		</div>
		<button type="submit" class="search">Search</search>
	</fieldset>
</form>
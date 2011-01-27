<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Parse an Address</h1>
<p>This form will display the parts of an address for a given string</p>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Address</legend>
		<div>
			<label for="address">Street Address</label>
			<input name="address" id="address"
					value="<?php echo isset($_GET['address']) ? View::escape($_GET['address']) : ''; ?>" />
		</div>
		<button type="submit" class="submit">Parse</search>
	</fieldset>
</form>

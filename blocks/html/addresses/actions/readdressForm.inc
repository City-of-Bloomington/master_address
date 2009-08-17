<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Change Of Address</h1>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php">
	<fieldset>
		<input type="hidden" name="action" value="change" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/actions/streetFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc'; ?>
	<fieldset id="changeLogEntry_fields">
		<legend>Change Log Entry</legend>
		<?php
			$block = new Block('addresses/actions/changeLogEntryFields.inc',
								array('action'=>'readdress'));
			echo $block->render('html');
		?>
	</fieldset>
</form>

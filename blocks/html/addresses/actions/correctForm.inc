<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Correct this address</h1>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php">
	<fieldset>
		<input type="hidden" name="action" value="correct" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<fieldset>
		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>
	<fieldset id="changeLogEntry_fields">
		<legend>Change Log Entry</legend>
		<?php
			$block = new Block('addresses/partials/changeLogEntryFields.inc',
								array('action'=>'correct'));
			echo $block->render('html');
		?>
	</fieldset>
</form>

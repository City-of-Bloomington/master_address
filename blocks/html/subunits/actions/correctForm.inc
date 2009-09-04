<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Correct this subunit</h1>
<form method="post" action="<?php echo BASE_URL; ?>/subunits/actions.php">
	<fieldset>
		<input type="hidden" name="action" value="correct" />
		<input type="hidden" name="subunit_id" value="<?php echo $this->subunit->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<fieldset id="changeLogEntry_fields">
		<legend>Change Log Entry</legend>
		<?php
			$block = new Block('changeLogs/changeLogEntryFields.inc',array('action'=>'correct'));
			echo $block->render('html');
		?>
	</fieldset>
</form>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
?>
<h1>Verify Street</h1>
<form method="post" action="<?php echo BASE_URL; ?>/streets/actions.php"
	onsubmit="getChangeLog(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="verify" />
		<input type="hidden" name="street_id" value="<?php echo $this->street->getId(); ?>" />

		<div>
		I, <?php echo View::escape($_SESSION['USER']->getFullname()); ?>,
		verify that this street is accurate at this point in time.
		</div>

		<button type="submit" class="save">Save</button>
	</fieldset>

	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',array('action'=>'verify'));
		echo $block->render('html');
	?>
</form>

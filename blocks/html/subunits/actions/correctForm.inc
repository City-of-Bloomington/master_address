<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Correct This Subunit</h1>
<form method="post" action="<?php echo BASE_URL; ?>/subunits/actions.php" onsubmit="getChangeLog(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="correct" />
		<input type="hidden" name="subunit_id" value="<?php echo $this->subunit->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',array('action'=>'correct'));
		echo $block->render('html');
	?>

</form>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
?>
<h1>Retire Subunit</h1>
<script type="text/javascript">
function handleRetireSubunit(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'retire');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/subunits/actions.php"
	onsubmit="handleRetireSubunit(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="retire" />
		<input type="hidden" name="subunit_id" value="<?php echo $this->subunit->getId(); ?>" />

		<div>
		I, <?php echo View::escape($_SESSION['USER']->getFullname()); ?>,
		verify that this subunit is to be retired at this point in time.
		</div>

		<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/notesField.inc'; ?>

		<button type="submit" class="save">Save</button>
	</fieldset>

	<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitData.inc'; ?>
</form>

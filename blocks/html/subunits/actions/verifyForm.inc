<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
?>
<h1>Verify Subunit</h1>
<script type="text/javascript">
function handleVerifySubunit(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'verify');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/subunits/actions.php"
	onsubmit="handleVerifySubunit(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="verify" />
		<input type="hidden" name="subunit_id" value="<?php echo $this->subunit->getId(); ?>" />

		<div>
		I, <?php echo View::escape($_SESSION['USER']->getFullname()); ?>,
		verify that this subunit is accurate at this point in time.
		</div>

		<button type="submit" class="save">Save</button>
	</fieldset>
	<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitData.inc'; ?>
</form>

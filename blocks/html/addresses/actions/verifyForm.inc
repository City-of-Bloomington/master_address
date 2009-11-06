<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Verify Address</h1>
<script type="text/javascript">
function handleVerifyAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'verify');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="handleVerifyAddress(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="verify" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<div>
			<p>
			I, <?php echo View::escape($_SESSION['USER']->getFullname()); ?>,
			verify that this address is accurate at this point in time.
			</p>
		</div>

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/notesField.inc'; ?>

		<button type="submit" class="save">Verify</button>
	</fieldset>
</form>
<table>
	<?php
		include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/requiredData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/unrequiredData.inc';
		include APPLICATION_HOME.'/blocks/html/locations/partials/locationData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/sanitationData.inc';
	?>
</table>
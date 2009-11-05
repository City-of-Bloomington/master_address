<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Update this address</h1>
<script type="text/javascript">
function handleUpdateAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'update');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="handleUpdateAddress(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<fieldset>
		<table>
			<?php
				include APPLICATION_HOME.'/blocks/html/addresses/partials/requiredFields.inc';
				include APPLICATION_HOME.'/blocks/html/addresses/partials/unrequiredFields.inc';

				$this->locationData = $this->address->getLocation()->getUpdatableData($this->address);
				include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
				include APPLICATION_HOME.'/blocks/html/addresses/partials/sanitationFields.inc';
			?>
		</table>

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/notesField.inc'; ?>

		<button type="submit" class="save">Save Changes</button>
	</fieldset>
</form>

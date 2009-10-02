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
		<?php
			include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc';

			$this->locationData = $this->address->getLocation()->getUpdatableData($this->address);
			include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';

			include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingFields.inc';
		?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>
</form>

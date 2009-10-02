<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Correct this address</h1>
<script type="text/javascript">
function handleCorrectAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'correct');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="handleCorrectAddress(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="correct" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<fieldset>
		<?php
			include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc';
			include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingData.inc';
		?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>
</form>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Change Of Address</h1>
<script type="text/javascript">
function handleReaddressAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'readdress');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="handleReaddressAddress(this); return false;">
	<fieldset>
	<table><tbody>
		<input type="hidden" name="action" value="readdress" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc'; ?>
	</table></tbody>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<?php
		include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingData.inc';
	?>

</form>

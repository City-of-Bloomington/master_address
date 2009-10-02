<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Reassign Address</h1>
<script type="text/javascript">
function handleReassignAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'reassign');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="handleReassignAddress(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="reassign" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<div>
		<p>This is a special way of unretiring this address.  We will keep this address as retired,
			but we'll create a new address with all the same information as this address.
		</p>
		</div>

		<button type="submit" class="save">Reassign</button>
	</fieldset>

	<?php
		include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingData.inc';
	?>
</form>

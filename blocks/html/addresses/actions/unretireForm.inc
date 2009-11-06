<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Unretire Address</h1>
<script type="text/javascript">
function handleUnretireAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'unretire');
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="handleUnretireAddress(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="unretire" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<div>
			<p>
			This will bring this address out of retirement by simply changing it's status to CURRENT.
			</p>
		</div>

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/notesField.inc'; ?>

		<button type="submit" class="save">Unretire</button>
	</fieldset>
	<?php
		include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/requiredData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/unrequiredData.inc';
		include APPLICATION_HOME.'/blocks/html/locations/partials/locationData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/sanitationData.inc';
	?>
</form>

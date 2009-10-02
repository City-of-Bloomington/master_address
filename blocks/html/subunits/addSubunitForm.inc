<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Add Subunit</h1>
<script type="text/javascript">
function handleAddSubunit(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,'assign');
	}
}
</script>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>"
	onsubmit="handleAddSubunit(this); return false;">
	<fieldset><legend>Subunit Information</legend>
		<input name="street_address_id" type="hidden" value="<?php echo $this->address->getId() ?>" />
		<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitFields.inc'; ?>
	</fieldset>

	<fieldset>
		<?php
			$locationData = $this->address->getLocation()->getUpdatableData($this->address);
			$locationData['locationType'] = new LocationType('RESIDENTIAL MULTI-FAMILY');
			$block = new Block('locations/partials/locationFields.inc',
								array('locationData'=>$locationData));
			echo $block->render('html');
		?>
	</fieldset>
	<fieldset>
		<button type="submit" class="submit">Submit</button>
	</fieldset>
</form>

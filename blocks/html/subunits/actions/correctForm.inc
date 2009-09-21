<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
?>
<h1>Correct This Subunit</h1>
<form method="post" action="<?php echo BASE_URL; ?>/subunits/actions.php"
	onsubmit="FRAMEWORK.getChangeLog(this,'correctSubunitForm'); return false;">
	<fieldset>
		<input type="hidden" name="action" value="correct" />
		<input type="hidden" name="subunit_id" value="<?php echo $this->subunit->getId(); ?>" />
		<input type="hidden" name="location_id" value="<?php echo $this->subunit->getLocation()->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitFields.inc'; ?>
	</fieldset>
	<fieldset>
		<?php
			$this->locationData = $this->subunit->getLocation()->getUpdatableData($this->subunit);
			include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
		?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>
	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',
							array('action'=>'correct','id'=>'correctSubunitForm'));
		echo $block->render('html');
	?>
</form>

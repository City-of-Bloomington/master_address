<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Update this address</h1>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="FRAMEWORK.checkAndgetChangeLog(this,'updateForm'); return false;">
	<fieldset>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<fieldset>
		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<fieldset>
		<?php
			$this->locationData = $this->address->getLocation()->getUpdatableData($this->address);
			include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
		?>
	</fieldset>

	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',
							array('action'=>'update','id'=>'updateForm'));
		echo $block->render('html');
	?>
</form>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Unretire Address</h1>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="getChangeLog(this); return false;">
	<fieldset>
		<input type="hidden" name="action" value="unretire" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<div>
		Make this address CURRENT
		</div>

		<button type="submit" class="save">Save</button>
	</fieldset>

	<?php
		include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc';
		include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc';

		$block = new Block('changeLogs/changeLogEntryFields.inc',array('action'=>'unretire'));
		echo $block->render('html');
	?>
</form>

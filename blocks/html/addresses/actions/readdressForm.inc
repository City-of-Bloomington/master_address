<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<h1>Change Of Address</h1>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/actions.php"
	onsubmit="FRAMEWORK.getChangeLog(this,'readdressForm'); return false;">
	<fieldset>
		<input type="hidden" name="action" value="readdress" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc'; ?>
		<button type="submit" class="save">Save Changes</button>
	</fieldset>

	<?php
		include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc';

		$block = new Block('changeLogs/changeLogEntryFields.inc',
							array('action'=>'readdress','id'=>'readdressForm'));
		echo $block->render('html');
	?>
</form>

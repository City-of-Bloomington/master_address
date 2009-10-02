<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param StreetName $this->streetName
 */
?>
<h2>Correct <?php echo $this->streetName->getStreetNameType(); ?> Name</h2>
<form method="post" action="<?php echo BASE_URL; ?>/streets/updateStreetName.php">
	<fieldset>
		<input type="hidden" name="streetName_id" value="<?php echo $this->streetName->getId(); ?>" />
		<?php
			include APPLICATION_HOME.'/blocks/html/streets/partials/streetNameFields.inc';
		?>
		<button type="submit" class="save">Save</button>
		<button type="button" class="cancel"
			onclick="document.location.href='<?php echo $this->streetName->getStreet()->getURL(); ?>';">
			Cancel
		</button>
	</fieldset>
</form>

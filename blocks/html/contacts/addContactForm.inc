<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Contact</h1>
<form method="post" action="<?php echo BASE_URL; ?>/contacts/addContact.php">
	<fieldset><legend>Contact Info</legend>
		<?php include APPLICATION_HOME.'/blocks/html/contacts/partials/contactFields.inc'; ?>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/contacts';">
			Cancel
		</button>
	</fieldset>
</form>

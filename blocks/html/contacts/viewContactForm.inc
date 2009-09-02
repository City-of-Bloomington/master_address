<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Contact $this->contact
 */
$editButton="";
if (userIsAllowed('Contact')) {
	$url = new URL(BASE_URL.'/contacts/updateContact.php');
	$url->contact_id = $this->contact->getContact_id();
	$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
}
?>
<h1><?php echo $editButton; ?>View Contact</h1>

<fieldset><legend>Contact Info</legend>

	<table>

		<tr><td><label for="contact-last_name" class="required">Last Name</label></td>
			<td><?php echo View::escape($this->contact->getLast_name()); ?></td>
		</tr>

		<tr><td><label for="contact-first_name" class="required">First Name</label></td>
			<td><?php echo View::escape($this->contact->getFirst_name()); ?></td>
		</tr>

		<tr><td><label for="contact-contact_type" class="required">Type</label></td>
		<td><?php echo $this->contact->getType(); ?></td>
			</tr>

		<tr><td><label for="contact-phone_number" class="required">Phone</label></td>
			<td><?php echo View::escape($this->contact->getPhone_number()); ?></td>
		</tr>

		<tr><td><label for="contact-agency" class="required">Organization</label></td>
			<td><?php echo View::escape($this->contact->getAgency()); ?></td>
		</tr>
		
		</tr>
		
	</table>
</fieldset>


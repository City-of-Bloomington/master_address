<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('Contact')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/contacts/addContact.php\">Add</a>";
			}
		?>
		Contacts
	</h1>
	<table>
		<tr><th></th>
			<th>Name</th>
			<th>Phone</th>
			<th>Type</th>
			<th>Organization</th>
		</tr>
		<?php
			foreach ($this->contactList as $contact) {

				$editButton = '';
				if (userIsAllowed('Contact')) {
					$url = new URL(BASE_URL.'/contacts/updateContact.php');
					$url->contact_id = $contact->getContact_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$url = new URL(BASE_URL.'/contacts/viewContact.php');
				$url->contact_id = $contact->getContact_id();
				$name = View::escape("{$contact->getFirstname()} {$contact->getLastname()}");
				$name = "<a href=\"$url\">$name</a>";
				$phone = View::escape($contact->getPhone_number());
				echo "
				<tr>
					<td>$editButton</td>
					<td>$name</td>
					<td>$phone</td>
					<td>{$contact->getType()}</td>
					<td>{$contact->getAgency()}</td>
				</tr>
				";
			}
		?>
	</table>
</div>

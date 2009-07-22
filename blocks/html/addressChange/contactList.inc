<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param ContactList $this->contactList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('Contact')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/addressChange/addContact.php\">Add</a>";
			}
		?>
		Contacts
	</h1>
	<table>
	   	<tr><th></th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Contact Type</th>
		    <th>Phone Number</th>
	   	    <th>Agency</th>
		</tr>
		<?php
			foreach ($this->contactList as $contact) {
				$editButton = '';
				if (userIsAllowed('Contact')) {
					$url = new URL(BASE_URL.'/addressChange/updateContact.php');
					$url->contact_id = $contact->getContact_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$firstname = View::escape($contact->getFirst_name());
				$lastname = View::escape($contact->getLast_name());
				$phone = View::escape($contact->getPhone_number());
				$agency = View::escape($contact->getAgency());
				echo"
				<tr><td>$editButton</td>
					<td>$firstname</td>
					<td>$lastname</td>
					<td>{$contact->getContact_type()}</td>
					<td>$phone</td>
	                <td>$agency</td>
				</tr>
				";
			}
		?>
	</table>
</div>

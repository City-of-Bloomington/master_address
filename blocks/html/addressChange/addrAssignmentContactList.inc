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
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/addressChange/addAddrAssignmentContact.php\">Add</a>";
			}
		?>
		Address Assignment Contacts
	</h1>
	<table>
	   	<tr>
		    <th></th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Contact Type</th>
		    <th>Phone Number</th>
	   	    <th>Agency</th>
		</tr>
	<?php
			foreach ($this->addrAssignmentContactList as $contact) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addressChange/updateAddrAssignmentContact.php');
					$url->contact_id = $contact->getContact_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo
				  "<tr>
				    <td>$editButton</td>
					<td>{$contact->getFirst_name()}</td>
					<td>{$contact->getLast_name()}</td>
					<td>{$contact->getContact_type()}</td>
					<td>{$contact->getPhone_number()}</td>
	                <td>{$contact->getAgency()}</td>
				   </tr>
				";
			}
		?>
	</table>
</div>
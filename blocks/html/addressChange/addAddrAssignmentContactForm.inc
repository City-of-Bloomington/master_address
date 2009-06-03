<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Address Assignment Contact</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Assignment Contact Info</legend>
		<table>

			<tr><td><label for="addr_assignment_contact-last_name">Last Name</label></td>
				<td><input name="addrAssignmentContact[last_name]" id="addr_assignment_contact-last_name" />
				</td>
			</tr>
				
			<tr><td><label for="addr_assignment_contact-first_name">First Name</label></td>
				<td><input name="addrAssignmentContact[first_name]" id="addr_assignment_contact-first_name" />
				</td>
			</tr>
				
			<tr><td><label for="addr_assignment_contact-contact_type">Contact Type</label></td>
				<td><input name="addrAssignmentContact[contact_type]" id="addr_assignment_contact-contact_type" />
				</td>
			</tr>
				
			<tr><td><label for="addr_assignment_contact-phone_number">Phone Number</label></td>
				<td><input name="addrAssignmentContact[phone_number]" id="addr_assignment_contact-phone_number" />
				</td>
			</tr>
				
			<tr><td><label for="addr_assignment_contact-agency">Agency</label></td>
				<td><input name="addrAssignmentContact[agency]" id="addr_assignment_contact-agency" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addressChange';">
			Cancel
		</button>
	</fieldset>
</form>
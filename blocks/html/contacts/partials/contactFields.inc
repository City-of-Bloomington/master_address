<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Contact $this->contact
 */
$contact = $this->contact ? $this->contact : new Contact();
?>
<table>

	<tr><td><label for="contact-last_name" class="required">Last Name</label></td>
		<td><input name="contact[last_name]" id="contact-last_name"
					value="<?php echo View::escape($contact->getLast_name()); ?>" />
		</td>
	</tr>

	<tr><td><label for="contact-first_name" class="required">First Name</label></td>
		<td><input name="contact[first_name]" id="contact-first_name"
					value="<?php echo View::escape($contact->getFirst_name()); ?>" />
		</td>
	</tr>

	<tr><td><label for="contact-contact_type" class="required">Type</label></td>
		<td><select name="contact[contact_type]" id="contact-contact_type">
				<?php
					foreach (Contact::getTypes() as $type) {
						$selected = $type == $contact->getType() ? 'selected="selected"' : '';
						echo "<option $selected>$type</option>";
					}
				?>
			</select>
		</td>
	</tr>

	<tr><td><label for="contact-phone_number" class="required">Phone</label></td>
		<td><input name="contact[phone_number]" id="contact-phone_number"
					value="<?php echo View::escape($contact->getPhone_number()); ?>" />
		</td>
	</tr>

	<tr><td><label for="contact-agency" class="required">Organization</label></td>
		<td><input name="contact[agency]" id="contact-agency"
					value="<?php echo View::escape($contact->getAgency()); ?>" />
		</td>
	</tr>

</table>

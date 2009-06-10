<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Contact $this->contact
 */
?>
<h1>Update Contact</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Contact Info</legend>
		<input name="contact_id" type="hidden" value="<?php echo $this->contact->getContact_id(); ?>" />
		<table>

			<tr><td><label for="contact-last_name">Last Name</label></td>
				<td><input name="contact[last_name]" id="contact-last_name" value="<?php echo View::escape($this->contact->getLast_name()); ?>" />
				</td>
			</tr>

			<tr><td><label for="contact-first_name">First Name</label></td>
				<td><input name="contact[first_name]" id="contact-first_name" value="<?php echo View::escape($this->contact->getFirst_name()); ?>" />
				</td>
			</tr>

			<tr><td><label for="contact-contact_type">Contact Type</label></td>
				<td><input name="contact[contact_type]" id="contact-contact_type" value="<?php echo View::escape($this->contact->getContact_type()); ?>" />
				</td>
			</tr>

			<tr><td><label for="contact-phone_number">Phone Number</label></td>
				<td><input name="contact[phone_number]" id="contact-phone_number" value="<?php echo View::escape($this->contact->getPhone_number()); ?>" />
				</td>
			</tr>

			<tr><td><label for="contact-agency">Agency</label></td>
				<td><input name="contact[agency]" id="contact-agency" value="<?php echo View::escape($this->contact->getAgency()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addressChange';">
			Cancel
		</button>
	</fieldset>
</form>

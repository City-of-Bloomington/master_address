<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Contact $this->contact
 */
$contact = $this->contact ? $this->contact : new Contact();
$fields = [
    'last_name', 'first_name', 'contact_type', 'phone_number', 'email',
    'address', 'city', 'state', 'zip',
    'notification', 'coordination',
    'contact_type', 'agency'
];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f = View::escape($this->contact->$get());
}
?>
<table>
    <tr><td><label for="status" class="required">Status</label></td>
        <td><select name="status" id="status">
            <?php
                foreach (Contact::getStatuses() as $status) {
                    $selected = $this->contact->getStatus_id() == $status->getId()
                        ? 'selected="selected"'
                        : '';
                    echo "<option>$status</option>";
                }
            ?>
            </select>
        </td>
    </tr>

	<tr><td><label for="last_name" class="required">Last Name</label></td>
		<td><input name="last_name" id="last_name" value="<?php echo $last_name; ?>" /></td>
	</tr>

	<tr><td><label for="first_name" class="required">First Name</label></td>
		<td><input name="first_name" id="first_name" value="<?php echo $first_name; ?>" /></td>
	</tr>

	<tr><td><label for="contact_type" class="required">Type</label></td>
		<td><select name="contact_type" id="contact_type">
				<?php
					foreach (Contact::getTypes() as $type) {
						$selected = $type == $contact_type ? 'selected="selected"' : '';
						echo "<option $selected>$type</option>";
					}
				?>
			</select>
		</td>
	</tr>

	<tr><td><label for="agency" class="required">Organization</label></td>
		<td><input name="agency" id="agency" value="<?php echo $agency; ?>" /></td>
	</tr>

	<tr><td><label for="phone_number" class="required">Phone</label></td>
		<td><input name="phone_number" id="phone_number" value="<?php echo $phone_number; ?>" /></td>
	</tr>

	<tr><td><label for="email">Email</label></td>
		<td><input name="email" id="email" value="<?php echo $email; ?>" /></td>
	</tr>
	<tr><td><label for="address">Address</label></td>
		<td><input name="address" id="address" value="<?php echo $address; ?>" /></td>
	</tr>
	<tr><td><label for="city">City</label></td>
		<td><input name="city" id="city" value="<?php echo $city; ?>" /></td>
	</tr>
	<tr><td><label for="state">State</label></td>
		<td><input name="state" id="state" size="2" value="<?php echo $state; ?>" />
			<label>Zip
				<input name="zip" size="5" value="<?php echo $zip; ?>" />
			</label>
		</td>
	</tr>
	<tr><td><label for="notification">Notification</label></td>
		<td><select name="notification" id="notification"><option value=""></option>
			<?php
				foreach (Contact::getNotificationValues() as $v) {
					$selected = $notification==$v
						? 'selected="selected"'
						: '';
					echo "<option $selected>$v</option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="coordination">Coordination</label></td>
		<td><select name="coordination" id="coordination"><option value=""></option>
			<?php
				foreach (Contact::getCoordinationValues() as $v) {
					$selected = $coordination==$v
						? 'selected="selected"'
						: '';
					echo "<option $selected>$v</option>";
				}
			?>
			</select>
		</td>
	</tr>
</table>

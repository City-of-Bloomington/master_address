<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param string $this->action
 * @param string $this->id
 */
$action = View::escape($this->action);
?>
<div id="<?php echo $this->id; ?>" style="position:absolute; visibility:hidden;">
	<div class="hd">Activity Log Entry</div>
	<div class="bd">
		<div><label for="changeLogEntry-action">Action:</label>
			<?php echo $action ?>
			<input type="hidden" name="changeLogEntry[action]" id="changeLogEntry-action"
					value="<?php echo $action; ?>" />
		</div>
		<div><p>Enter comments or rationale for the activity log:</p>
			<textarea name="changeLogEntry[notes]" rows="4" cols="50"></textarea>
		</div>
		<div><label for="changeLogEntry-contact_id">Contact</label>
			<select name="changeLogEntry[contact_id]" id="changeLogEntry-contact_id">
				<option></option>
				<?php
					$list = new ContactList();
					$list->find();
					foreach ($list as $contact) {
						$name = View::escape("{$contact->getFirstname()} {$contact->getLastname()}");
						echo "<option value=\"{$contact->getId()}\">$name</option>";
					}
				?>
			</select>
		</div>
		<button type="button" onclick="this.form.submit();">Continue Saving</button>
		<button type="button" onclick="changeLogForm.hide();">Cancel</button>
	</div>
	<div class="ft"></div>
</div>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->street
 */
?>
<div>
	<div><label for="street-notes">Notes</label></div>
	<textarea name="street[notes]" id="street-notes" rows="3" cols="50"><?php echo View::escape($this->street->getNotes()); ?></textarea>
</div>

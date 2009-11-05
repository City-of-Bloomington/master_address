<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
$subunit = $this->subunit ? $this->subunit : new Subunit();
?>
<div>
	<div><label for="notes">Notes</label></div>
	<textarea name="notes" id="notes" rows="3" cols="50"><?php echo View::escape($subunit->getNotes()); ?></textarea>
</div>
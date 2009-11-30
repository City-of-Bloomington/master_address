<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
$notes = View::escape($this->subunit->getNotes());
echo "<p>$notes</p>";
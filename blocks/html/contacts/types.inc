<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->types
 */
?>
<h2>Contact Types</h2>
<ul>
<?php
	foreach ($this->types as $type) {
		$type = View::escape($type);
		echo "<li>$type</li>";
	}
?>
</ul>
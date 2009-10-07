<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubdivisionList $this->subdivisionList
 */
?>
<div class="interfaceBox">
	<h1>Subdivisions</h1>
	<ul><?php
			foreach ($this->subdivisionList as $subdivision) {
				echo "<li>$subdivision</li>";
			}
		?>
	</ul>
</div>
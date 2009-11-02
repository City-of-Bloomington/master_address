<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PurposeList $this->purposeList
 */
?>
<div class="interfaceBox">
	<h1>Additional Information</h1>
	<?php
		if (count($this->purposeList)) {
			echo '<ul>';
			foreach ($this->purposeList as $purpose) {
				echo "<li>$purpose</li>";
			}
			echo '</ul>';
		}
	?>
</div>
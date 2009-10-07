<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param JurisdictionList $this->jurisdictionList
 */
?>
<div class="interfaceBox">
	<h1>Jurisdictions</h1>
	<ul><?php
			foreach ($this->jurisdictionList as $jurisdiction) {
				echo "<li>$jurisdiction</li>";
			}
		?>
	</ul>
</div>

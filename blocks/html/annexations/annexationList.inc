<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AnnexationList $this->annexationList
 */
?>
<div class="interfaceBox">
	<h1>Annexations</h1>
	<ul><?php
			foreach ($this->annexationList as $annexation) {
				$ordinance = View::escape($annexation->getOrdinance_number());
				echo "<li>$ordinance</li>";
			}
		?>
	</ul>
</div>
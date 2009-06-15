<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AnnexationList $this->annexationList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/annexations/addAnnexation.php\">Add</a>";
			}
		?>
		Annexations
	</h1>
	<ul>
		<?php
			foreach ($this->annexationList as $annexation) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/annexations/updateAnnexation.php');
					$url->annexation_id = $annexation->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$ordinance = View::escape($annexation->getOrdinance_number());
				echo "
				<li>$editButton $ordinance</li>
				";
			}
		?>
	</ul>
</div>
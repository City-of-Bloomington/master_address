<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('Jurisdiction')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/jurisdictions/addJurisdiction.php\">Add</a>";
			}
		?>
		Jurisdictions
	</h1>
	<ul><?php
			foreach ($this->jurisdictionList as $jurisdiction) {
				$editButton = '';
				if (userIsAllowed('Jurisdiction')) {
					$url = new URL(BASE_URL.'/jurisdictions/updateJurisdiction.php');
					$url->jurisdiction_id = $jurisdiction->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $jurisdiction</li>";
			}
		?>
	</ul>
</div>

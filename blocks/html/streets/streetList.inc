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
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/mast_street/addMastStreet.php\">Add</a>";
			}
		?>
		MastStreets
	</h1>
	<ul><?php
			foreach ($this->mast_streetList as $mast_street) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/mast_street/updateMastStreet.php');
					$url->street_id = $mast_street->getStreet_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $mast_street</li>";
			}
		?>
	</ul>
</div>
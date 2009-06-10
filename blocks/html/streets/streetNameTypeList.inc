<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		Street Name Types
	</h1>
	<ul><?php
			foreach ($this->streetNameTypeList as $nameType) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streets/updateStreetNameType.php');
					$url->street_name_type = $nameType->getStreet_name_type();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $nameType</li>";
			}
		?>
	</ul>
</div>
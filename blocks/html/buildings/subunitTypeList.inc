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
				echo "<a class=\"add button\" href=\"".BASE_URL."/buildings/addSubunitType.php\">Add</a>";
			}
		?>
		Subunit Types
	</h1>
	<ul><?php
			foreach ($this->subunitTypeList as $Type) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/buildings/updateSubunitType.php');
					$url->sudtype = $subunitType->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $subunitType</li>";
			}
		?>
	</ul>
</div>

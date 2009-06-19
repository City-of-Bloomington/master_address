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
				echo "<a class=\"add button\" href=\"".BASE_URL."/subdivisions/addSubdivision.php\">Add</a>";
			}
		?>
		Subdivisions
	</h1>
	<ul><?php
			foreach ($this->subdivisionList as $subdivision) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/subdivisions/updateSubdivision.php');
					$url->subdivision_id = $subdivision->getSubdivision_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $subdivision</li>";
			}
		?>
	</ul>
</div>
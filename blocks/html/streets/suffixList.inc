<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SuffixList $this->suffixList
 */
?>
<div class="interfaceBox">
	<h1>
   		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/streets/addSuffix.php\">Add</a>";
			}
		?>
		Suffixes
	</h1>
	<ul><?php
			foreach ($this->suffixList as $suffix) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streets/updateSuffix.php');
					$url->id = $suffix->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $suffix</li>";
			}
		?>
	</ul>
</div>

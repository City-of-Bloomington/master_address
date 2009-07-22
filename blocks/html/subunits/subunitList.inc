<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitList $this->subunitList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('Subunit')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/subunits/addSubunit.php?street_address_id={$this->address->getId()}\">Add</a>";
			}
		?>
		Subunits
	</h1>
	<ul><?php
			foreach ($this->subunitList as $subunit) {
				$editButton = '';
				if (userIsAllowed('Subunit')) {
					$url = new URL(BASE_URL.'/subunits/updateSubunit.php');
					$url->subunit_id = $subunit->getSubunit_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<li>$editButton $subunit</li>";
			}
		?>
	</ul>
</div>
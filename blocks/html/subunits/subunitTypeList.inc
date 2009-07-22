<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitTypeList $this->subunitTypeList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('SubunitType')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/subunits/addSubunitType.php\">Add</a>";
			}
		?>
		Subunit Types
	</h1>
	<table>
		<tr><th></th>
			<th>Type</th>
			<th>Description</th>
		</tr>
		<?php
			foreach ($this->subunitTypeList as $subunitType) {
				$editButton = '';
				if (userIsAllowed('SubunitType')) {
					$url = new URL(BASE_URL.'/subunits/updateSubunitType.php');
					$url->subunitType_id = $subunitType->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$type = View::escape($subunitType->getType());
				$description = View::escape($subunitType->getDescription());
				echo "
				<tr><td>$editButton</td>
					<td>$type</td>
					<td>$description</td>
				</tr>
				";
			}
		?>
	</table>
</div>
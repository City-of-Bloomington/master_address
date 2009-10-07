<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreeNameTypeList $this->streetNameTypeList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('StreeNameType')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/streets/addStreetNameType.php';\">
					Add
				</button>
				";
			}
		?>
		Street Name Types
	</h1>
	<table>
		<thead>
			<tr><th></th>
				<th>Type</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->streetNameTypeList as $nameType) {
				$editButton = '';
				if (userIsAllowed('StreeNameType')) {
					$url = new URL(BASE_URL.'/streets/updateStreetNameType.php');
					$url->id = $nameType->getId();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
				}
				$type = View::escape($nameType->getType());
				$description = View::escape($nameType->getDescription());
				echo "
				<tr><td>$editButton</td>
					<td>$type</td>
					<td>$description</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
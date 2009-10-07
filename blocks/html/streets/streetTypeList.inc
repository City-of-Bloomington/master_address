<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param streetTypeList $this->streetTypeList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('StreetType')) {
				echo "
				<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/streets/addStreetType.php';\">
					Add
				</button>
				";
			}
		?>
		Street Types
	</h1>
	<table>
		<thead>
			<tr><th></th>
				<th>Code</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->streetTypeList as $streetType) {
				$editButton = '';
				if (userIsAllowed('StreetType')) {
					$url = new URL(BASE_URL.'/streets/updateStreetType.php');
					$url->streetType_id = $streetType->getId();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
				}
				$code = View::escape($streetType->getCode());
				$description = View::escape($streetType->getDescription());
				echo "
				<tr><td>$editButton</td>
					<td>$code</td>
					<td>$description</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>

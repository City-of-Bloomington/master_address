<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param streetTypeList $this->streetTypeList
 */
?>
<div class="interfaceBox">
	<h1>
   		<?php
			if (userIsAllowed('StreetType')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/streets/addStreetType.php\">Add</a>";
			}
		?>
		Street Types
	</h1>
	<table>
		<tr><th></th>
			<th>Code</th>
			<th>Description</th>
		</tr>
		<?php
			foreach ($this->streetTypeList as $streetType) {
				$editButton = '';
				if (userIsAllowed('StreetType')) {
					$url = new URL(BASE_URL.'/streets/updateStreetType.php');
					$url->streetType_id = $streetType->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
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
	</table>
</div>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubdivisionNameList $this->subdivisionNameList
 */
?>
<div class="interfaceBox">
	<h1><?php
			if (userIsAllowed('SubdivisionName')) {
				if (isset($this->subdivision)) {
					echo "
					<button type=\"button\" class=\"add\" onclick=\"document.location.href='".BASE_URL."/subdivisions/addSubdivisionName.php?subdivision_id={$this->subdivision->getId()}';\">
						Add
					</button>
					";
				}
			}
		?>
		Subdivision Names
	</h1>
	<table>
		<thead>
			<tr><th></th>
				<th>Subdivision</th>
				<th>Name</th>
				<th>Phase</th>
				<th>Status</th>
				<th>Effective Start Date</th>
				<th>Effective End Date</th>
			<tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->subdivisionNameList as $subName) {
				$editButton = '';
				$editButton2 = '';
				if (userIsAllowed('SubdivisionName')) {
					$url = new URL(BASE_URL.'/subdivisions/updateSubdivisionName.php');
					$url->subdivision_name_id = $subName->getSubdivision_name_id();
					$editButton = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
					$url = new URL(BASE_URL.'/subdivisions/updateSubdivision.php');
					$url->subdivision_id = $subName->getSubdivision_id();
					$editButton2 = "
					<button type=\"button\" class=\"edit\" onclick=\"document.location.href='$url';\">
						Edit
					</button>
					";
				}
				echo "
				<tr><td>$editButton</td>
					<td>$editButton2 {$subName->getSubdivision()}</td>
					<td>{$subName->getName()}</td>
					<td>{$subName->getPhase()}</td>
					<td>{$subName->getStatus()}</td>
					<td>{$subName->getEffective_start_date('n/j/Y')}</td>
					<td>{$subName->getEffective_end_date('n/j/Y')}</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
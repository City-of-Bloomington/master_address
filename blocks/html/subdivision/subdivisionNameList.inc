<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubdivisionNameList $this->subdivisionNameList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('SubdivisionName')) {
				if (isset($this->subdivision)) {
					echo "<a class=\"add button\" href=\"".BASE_URL."/subdivisions/addSubdivisionName.php?subdivision_id={$this->subdivision->getId()}\">Add</a>";
				}
			}
		?>
		Subdivision Names
	</h1>
	<table>
		<tr><th></th>
			<th>Subdivision</th>
			<th>Name</th>
			<th>Phase</th>
			<th>Status</th>
			<th>Effective Start Date</th>
			<th>Effective End Date</th>
		<tr>
	   <?php
			foreach ($this->subdivisionNameList as $subName) {
				$editButton = '';
				$editButton2 = '';
				if (userIsAllowed('SubdivisionName')) {
					$url = new URL(BASE_URL.'/subdivisions/updateSubdivisionName.php');
					$url->subdivision_name_id = $subName->getSubdivision_name_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
					$url = new URL(BASE_URL.'/subdivisions/updateSubdivision.php');
					$url->subdivision_id = $subName->getSubdivision_id();
					$editButton2 = "<a class=\"edit button\" href=\"$url\">Edit</a>";
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
	</table>
</div>
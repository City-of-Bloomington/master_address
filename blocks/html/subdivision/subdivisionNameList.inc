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
				echo "<a class=\"add button\" href=\"".BASE_URL."/subdivisions/addSubdivisionName.php\">Add</a>";
			}
		?>
		Subdivision Names
	</h1>
		<table>
		    <tr>
		        <th></th>
		        <th>Subdivision</th>
                <th>Name</th>
		        <th>Phase</th>
		        <th>Status</th>
		        <th>Start Date</th>
		        <th>End Date</th>
		    <tr>
	   <?php
			foreach ($this->subdivisionNameList as $subName) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/subdivisions/updateSubdivisionName.php');
					$url->subdivision_name_id = $subName->getSubdivision_name_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<tr>
                         <td>$editButton</td>
                         <td>{$subName->getSubdivision()}</td>
                         <td>{$subName->getPhase()}</td>
                         <td>{$subName->getStatus()}</td>
                         <td>{$subName->getEffective_start_date('n/j/Y')}</td>
                         <td>{$subName->getEffective_end_date('n/j/Y')}</td>
                     </tr>";
			}
		?>
	</ul>
</div>
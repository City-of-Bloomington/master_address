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
            $street_id=$this->street->getId();
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/streets/addStreetName.php?street_id=$street_id\">Add</a>";
			}
		?>
		Street Names
	</h1>
	<table>
		<tr><th></th>
		    <th>Direction</th>
		    <th>Street Name</th>
		    <th>Street Type</th>
		    <th>Post Direction</th>
		    <th>Name Type</th>
		    <th>Effective Start Date</th>
		    <th>Effective End Date</th>
		    <th>Notes</th>
		 </tr>
		<?php
			foreach ($this->streetNameList as $streetName) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/streets/updateStreetName.php');
					$url->id = $streetName->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$name = View::escape($streetName->getStreet_name());
				$notes = View::escape($streetName->getNotes());
				echo "<tr>
                         <td>$editButton</td>
                         <td>{$streetName->getDirection()->getCode()}</td>
                         <td>$name</td>
                         <td>{$streetName->getStreetType()->getDescription()}</td>
                         <td>{$streetName->getPostDirection()->getDescription()}</td>
				         <td>{$streetName->getStreetNameType()}</td>
                         <td>{$streetName->getEffective_start_date('n/j/Y')}</td>
                         <td>{$streetName->getEffective_end_date('n/j/Y')}</td>
                         <td>$notes</td>
				     </tr>";
			}
		?>
	</table>
</div>
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$subunit_id=$this->subunit->getId();
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
			  // if(!count($this->subunitStatusList)){
				echo "<a class=\"add button\" href=\"".BASE_URL."/subunits/addSubunitStatus.php?subunit_id=$subunit_id\">Add</a>";
				//}
			}
		?>
		Subunit Statuses
	</h1>
	<table><tr><th></th>
		       <th>Subunit ID</th>
		       <th>Address ID</td>
		       <th>Status</th>
		       <th>Start Date</th>
		       <th>End Date</th>
		   </tr>
		   <?php
			foreach ($this->subunitStatusList as $subunitStatus) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/subunits/updateSubunitStatus.php');
					$url->subunit_id = $subunitStatus->getSubunit_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<tr>
				          <td>$editButton</td>
                          <td>{$subunitStatus->getId()}</td>
                          <td>{$subunitStatus->getStreet_address_id()}</td>
                          <td>{$subunitStatus->getStatus_code()}</td>
                          <td>{$subunitStatus->getStart_date()}</td>
                          <td>{$subunitStatus->getEnd_date()}</td>
                     </tr>";
			}
		?>
	</table>
</div>
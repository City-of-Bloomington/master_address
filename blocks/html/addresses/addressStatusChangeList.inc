<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		Address Status Changes
	</h1>
	<table>
		 <tr><th></th>
		     <th>Status</th>
		     <th>Start Date</th>
		     <th>End Date</th>
		  </tr>
		    <?php
			foreach ($this->addressStatusChangeList as $addressStatusChange) {

				$editButton = '';

				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addresses/updateAddressStatusChange.php');
					$url->id = $addressStatusChange->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}

				echo "<tr><td>$editButton</td>
                          <td>{$addressStatusChange->getAddressStatus()}</td>
                          <td>{$addressStatusChange->getStart_date('n/j/Y')}</td>
                          <td>{$addressStatusChange->getEnd_date('n/j/Y')}</td>
                     </tr>";
			}
		?>
	</ul>
</div>
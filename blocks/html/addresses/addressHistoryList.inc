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
				echo "<a class=\"add button\" href=\"".BASE_URL."/addresses/addAddressHistory.php?street_address_id={$this->street_address_id}\">Add</a>";
			}
		?>
		Address Status Changes
	</h1>
	<table>
		 <tr><th></th>
		     <th>Street Address ID</th>
		     <th>Status</th>
		     <th>Start Date</th>
		     <th>End Date</th>
		  </tr>
		    <?php
			foreach ($this->addressHistoryList as $addressHistory) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addresses/updateAddressHistory.php');
					$url->id = $addressHistory->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "<tr><td>$editButton</td>
                          <td>{$addressHistory->getStreet_address_id()}</td>
                          <td>{$addressHistory->getStatus()}</td>
                          <td>{$addressHistory->getStart_date('n/j/Y')}</td>
                          <td>{$addressHistory->getEnd_date('n/j/Y')}</td>
                     </tr>";
			}
		?>
	</ul>
</div>
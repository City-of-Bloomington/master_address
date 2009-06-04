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
				echo "<a class=\"add button\" href=\"".BASE_URL."/sanitation/addAddressSanitation.php\">Add</a>";
			}
		?>
		Address Sanitations
	</h1>
	<table>
		<tr>
		    <th></th>
		    <th>Address Id</th>
		    <th>Trash Pickup Day</th>
		    <th>Recycl Week</th>
		</tr>
		<?php
			foreach ($this->addressSanitationList as $sanitation) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/sanitation/updateAddressSanitation.php');
					$url->street_address_id = $sanitation->getStreet_address_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo
				    "<tr>
				        <td>$editButton</td>
                        <td>{$sanitation->getId()}</td>
                        <td>{$sanitation->getTrash_pickup_day()}</td>
                        <td>{$sanitation->getRecycle_week()}</td>
                    </tr>";
				
			}
		?>
	</table>
</div>
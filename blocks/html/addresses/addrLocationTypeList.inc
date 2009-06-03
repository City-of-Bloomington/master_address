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
				echo "<a class=\"add button\" href=\"".BASE_URL."/addresses/addAddrLocationType.php\">Add</a>";
			}
		?>
		Address Location Types
	</h1>
		<table>
		<tr>
		    <th></th>
		    <th>Type</th>
		    <th>Description</th>
		</tr>
	    <?php
			foreach ($this->addrLocationTypeList as $addrType) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addresses/updateAddrLocationType.php');
					$url->location_type_id = $addrType->getLocation_type_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$type = View::escape($addrType->getLocation_type_id());
				$description = View::escape($addrType->getDescription());
				
				echo
				  "<tr>
				      <td>$editButton</td>
                      <td>$type</td>
                      <td>$description</td>
                  </tr>";
			}
		?>
	</table>
</div>
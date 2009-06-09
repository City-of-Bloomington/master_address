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
				echo "<a class=\"add button\" href=\"".BASE_URL."/addresses/addAddress.php\">Add</a>";
			}
		?>
		Addresss
	</h1>
	   <table>
		<tr>
           <th></th>
		   <th>Street Number</th>
		   <th>Street</th>
		   <th>Type</th>
		   <th>Tax Jurisdiction</th>
		   <th>Gov Jur ID</th>
		   <th>Township</th>
		   <th>Section</th>
		   <th>Quarter Section</th>
		   <th>Zip code</th>
		   <th>Census Block</th>
		   <th>State Plane X, Y</th>
           <th>Lat,  Long </th>
		   <th>Subdivision</th>
		   <th>Plat</th>
		   <th>Street Address 2</th>
		   <th>City</th>   
           <th>Status</th>
           <th>Notes</th>
		</tr>
        <?php
		
			foreach ($this->addressList as $address) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addresses/updateAddress.php');
					$url->street_address_id = $address->getStreet_address_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}

				echo
				  "<tr>
				    <td>$editButton</td>
					<td>{$address->getStreet_number()}</td>
	                <td>{$address->getStreet()}</td>
	                <td>{$address->getAddress_type()}</td>
	                <td>{$address->getTax_jurisdiction()}</td>
	                <td>{$address->getGov_jur_id()}</td>
	                <td>{$address->getTownship()}</td>
	                <td>{$address->getSection()}</td>
	                <td>{$address->getQuarter_section()}</td>
	                <td>{$address->getCity()}</td>
	                <td>{$address->getZips()}</td>
	                <td>{$address->getCensus_block_fips_code()}</td>
	                <td>{$address->getState_plane_xy_coordinate()}</td>
	                <td>{$address->getLatLong()}</td>
	                <td>{$address->getSubdivision()}</td>
	                <td>{$address->getPlat()}</td>
	                <td>{$address->getStreet_address_2()}</td>
	                <td>{$address->getStatus()}</td>
	                <td>{$address->getNotes()}</td>
				   </tr>
				";	
			}
		?>
	</table>
</div>
<div class="interfaceBox">
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetList $this->streetList
 */
if (count($this->streetList)) {
	echo "
	<table>
		<tr>
		    <th>Street Name</th>
			<th>Status</th>
            <th>Town</th>
		</tr>
	";
		foreach ($this->streetList as $street) {
			$name = View::escape($street->getStreetName());
			$status = View::escape($street->getStatus()->getDescription());
			$town = View::escape($street->getTown()->getDescription());
			echo "
			<tr><td><a href=\"{$street->getURL()}\">$name</a></th>
				<td>$status</td>
                <td>$town</td>
			</tr>
			";
		}
	echo "
	</table>
	";
}
else {
	echo "<h2>No Streets Found</h2>";
}
	if (userHasRole('Administrator')) {
		$addURL = BASE_URL.'/streets/addStreet.php';
		echo "
		<p>Can't find what you're looking for?
			<a href=\"$addURL\">Create New Street</a>
		</p>
		";
	}
?>
</div>
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitList $this->subunitList
 * @param Address $this->address
 */
?>
<div class="interfaceBox subunitList">
	<h1>Subunits</h1>
	<?php
		if (isset($this->address) && userIsAllowed('Subunit')) {
			echo "
			<div>
				<button type=\"button\" onclick=\"document.location.href='".BASE_URL."/subunits/addSubunit.php?street_address_id={$this->address->getId()}';\">
					Add Subunit
				</button>
			</div>
			";
		}
	?>
	<table>
		<tr><th>Type</th>
			<th>#</th>
			<th>Status</th>
		</tr>
		<?php
			foreach ($this->subunitList as $subunit) {
				$number = View::escape($subunit->getIdentifier());
				echo "
				<tr><td>{$subunit->getType()}</td>
					<td>$number</td>
					<td>{$subunit->getStatus()}</td>
				</tr>
				";
			}
		?>
	</table>
</div>
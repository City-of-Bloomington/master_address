<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param PrecinctList $this->precinctList
 */
?>
<div class="interfaceBox">
	<h1>Precincts</h1>
	<table>
		<thead>
			<tr><th></th>
				<th>Code</th>
				<th>Name</th>
				<th>Active</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->precinctList as $precinct) {
				$code = View::escape($precinct->getCode());
				$name = View::escape($precinct->getName());
				$active = View::escape($precinct->getActive());
				echo "
				<tr><td>$editButton</td>
					<td>$code</td>
					<td>$name</td>
					<td>$active</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->statuses
 */
?>
<section>
    <header>
        <h2><?= $this->_('status'); ?></h2>
    </header>
	<table>
		<thead>
			<tr>
				<th><?= $this->_('status'); ?></th>
				<th><?= $this->_('startDate'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->statuses as $s) {
                $start = $s['start_date'] ? $s['start_date']->format(DATE_FORMAT) : '';
				echo "
				<tr><td>$s[status]</td>
					<td>$start</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>

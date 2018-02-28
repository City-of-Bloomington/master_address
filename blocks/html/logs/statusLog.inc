<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
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
				<th><?= $this->_('endDate'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->statuses as $s) {
                $start = $s['start_date'] ? $s['start_date']->format(DATE_FORMAT) : '';
                $end   = $s['end_date'  ] ? $s['end_date'  ]->format(DATE_FORMAT) : '';
				echo "
				<tr><td>$s[status]</td>
					<td>$start</td>
					<td>$end</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>

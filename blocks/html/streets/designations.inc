<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Street $this->street
 * @param array  $this->designations
 */
?>
<section>
    <header>
        <h2><?= $this->_(['designation', 'designations', count($this->designations)]); ?></h2>
    </header>
	<table>
		<thead>
			<tr>
				<th><?= $this->_('type'     ); ?></th>
				<th><?= $this->_('name'     ); ?></th>
				<th><?= $this->_('startDate'); ?></th>
				<th><?= $this->_('endDate'  ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->designations as $d) {
                $start = $d->start_date ? $d->start_date->format(DATE_FORMAT) : '';
                $end   = $d->end_date   ? $d->end_date  ->format(DATE_FORMAT) : '';

                $name = parent::escape(implode(' ', [
                    $d->direction,
                    $d->name,
                    $d->suffix_code,
                    $d->post_direction
                ]));
				$type  = parent::escape($d->type);

				echo "
				<tr>
                    <td>$type</td>
					<td>$name</td>
					<td>$start</td>
					<td>$end</td>
					<td></td>
                </tr>
				";
			}
		?>
		</tbody>
	</table>
</section>

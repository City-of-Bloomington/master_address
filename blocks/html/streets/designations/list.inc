<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.html GNU/AGPL, see LICENSE
 * @param array  $this->designations
 * @param bool   $this->disableButtons Optional
 */
?>
<section>
    <header>
        <h2><?= $this->_(['designation', 'designations', count($this->designations)]); ?></h2>
    </header>
	<table>
		<thead>
			<tr><th><?= $this->_('type'     ); ?></th>
				<th><?= $this->_('name'     ); ?></th>
				<th><?= $this->_('startDate'); ?></th>
				<th><?= $this->_('rank'     ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
            $userCanEdit = !$this->disableButtons && parent::isAllowed('designations', 'update');
            $button      = '';
            $h           = $this->template->getHelper('buttonLink');

			foreach ($this->designations as $d) {
                $name  = parent::escape($d);
				$type  = parent::escape($d->type);
                $start = $d->start_date ? $d->start_date->format(DATE_FORMAT) : '';
                $rank  = $d->rank;

                if ($type == 'STREET') {
                    if (!$this->disableButtons && parent::isAllowed('streets', 'changeName')) {
                        $button = $h->buttonLink(
                            parent::generateUri('streets.changeName', ['id'=>$d->street_id]),
                            $this->_('changeName'),
                            'edit'
                        );
                    }
                }
                else {
                    if ($userCanEdit) {
                        $button = $h->buttonLink(
                            parent::generateUri('designations.update', ['id'=>$d->id]),
                            $this->_('update'),
                            'edit'
                        );
                    }
                }

                if (parent::isAllowed('streetNames', 'view')) {
                    $uri = parent::generateUri('streetNames.view', ['id'=>$d->name_id]);
                    $name = "<a href=\"$uri\">$name</a>";
                }

				echo "
				<tr><td>$type</td>
					<td>$name</td>
					<td>$start</td>
                    <td>$rank</td>
					<td>$button</td>
                </tr>
				";
			}
		?>
		</tbody>
	</table>
</section>

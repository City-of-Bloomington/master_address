<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->changes
 */
?>
<section>
    <header>
        <h2><?= $this->_('changeLog'); ?></h2>
    </header>
	<table>
		<thead>
			<tr><th><?= $this->_('date'); ?></th>
				<th></th>
				<th><?= $this->_('action' ); ?></th>
				<th><?= $this->_('person' ); ?></th>
				<th><?= $this->_('contact'); ?></th>
				<th><?= $this->_('notes'  ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
            foreach ($this->changes as $c) {
                $date    = $c->action_date->format(DATE_FORMAT);
                $person  = parent::escape("{$c->person_firstname} {$c->person_lastname}");
                $contact = parent::escape("{$c->contact_firstname} {$c->contact_lastname}");
                $action  = parent::escape($c->action);
                $notes   = parent::escape($c->notes);
                echo "
                <tr><th>$date</th>
                    <td></td>
                    <td>$action</td>
                    <td>$person</td>
                    <td>$contact</td>
                    <td>$notes</td>
                </tr>
                ";
            }
		?>
        </tbody>
    </table>
</section>

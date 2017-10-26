<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Street|Address|Subunit|Contact $this->target
 * @param array                          $this->changes
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
                $date    = $c->getActionDate()->format(DATE_FORMAT);
                $person  = $c->getPerson_id () ? parent::escape($c->getPerson ()->getFullname()) : '';
                $contact = $c->getContact_id() ? parent::escape($c->getContact()->getFullname()) : '';
                $action  = parent::escape($c->getAction());
                $notes   = parent::escape($c->getNotes());
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

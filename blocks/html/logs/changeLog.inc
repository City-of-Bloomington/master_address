<?php
/**
 * Displays entries from a change log without any entity info.
 *
 * This type of change log is typically displayed on info views for entitie.
 * Because we're already only displaying a single entity, there is no need
 * to include the entity info in the change log display.
 *
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->entries       An array of Domain\Logs\Entities\ChangeLogEntry
 * @param int   $this->total
 * @param int   $this->itemsPerPage
 * @param int   $this->currentPage
 */
use Application\Block;
use Application\Paginator;
?>
<section>
    <header>
        <h2><?= $this->_('changeLog'); ?></h2>
    </header>
	<table>
		<thead>
			<tr><th><?= $this->_('date'); ?></th>
				<th><?= $this->_('action' ); ?></th>
				<th><?= $this->_('user'   ); ?></th>
				<th><?= $this->_('contact'); ?></th>
				<th><?= $this->_('notes'  ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
            foreach ($this->entries as $c) {
                $date    = $c->action_date->format(DATE_FORMAT);
                $person  = parent::escape("{$c->person_firstname} {$c->person_lastname}");
                $contact = parent::escape("{$c->contact_firstname} {$c->contact_lastname}");
                $action  = parent::escape($c->action);
                $notes   = parent::escape($c->notes);
                echo "
                <tr><th>$date</th>
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
    <?php
        if ($this->itemsPerPage && $this->total > $this->itemsPerPage) {
            $paginator = new Block('pageNavigation.inc', [
                'paginator' => new Paginator(
                    $this->total,
                    $this->itemsPerPage,
                    $this->currentPage
            )]);
            echo $paginator->render('html', $this->template);
        }
    ?>
</section>

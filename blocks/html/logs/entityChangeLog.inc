<?php
/**
 * Displays entries from a change log, including the entity information
 *
 * This type of display is used when showing a full dump of a change log.
 * Since we're showing changes across a bunch of entities, we need to include
 * the entity info for each change log entry.
 *
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param array $this->entries       An array of Domain\Logs\Entities\ChangeLogEntry
 * @param int   $this->total
 * @param int   $this->itemsPerPage
 * @param int   $this->currentPage
 * @param bool  $this->moreLink
 */
declare (strict_types=1);
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
                <th></th>
				<th><?= $this->_('action' ); ?></th>
				<th><?= $this->_('person' ); ?></th>
				<th><?= $this->_('contact'); ?></th>
				<th><?= $this->_('notes'  ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
            foreach ($this->entries as $c) {
                $date    = $c->action_date->format(DATE_FORMAT);
                $entity  = parent::escape($c->entity->__toString());
                $person  = parent::escape("{$c->person_firstname} {$c->person_lastname}");
                $contact = parent::escape("{$c->contact_firstname} {$c->contact_lastname}");
                $action  = parent::escape($c->action);
                $notes   = parent::escape($c->notes);
                echo "
                <tr><th>$date</th>
                    <td>$entity</td>
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
            if ($this->moreLink) {
                $uri = parent::generateUri('addresses.changeLog');
                echo "<a href=\"$uri\">{$this->_('more')}</a>";
            }
            else {
                $paginator = new Block('pageNavigation.inc', [
                    'paginator' => new Paginator(
                        $this->total,
                        $this->itemsPerPage,
                        $this->currentPage
                )]);
                echo $paginator->render('html', $this->template);
            }
        }
    ?>
</section>

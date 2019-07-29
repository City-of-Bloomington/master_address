<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int   $this->street_id
 * @param array $this->designations  An array of Designation objects
 */
declare (strict_types=1);
use Domain\Streets\Metadata;
?>
<section>
    <header>
        <h1><?= $this->_('reorder'); ?></h1>
        <p><?= $this->_('streets.reorder', 'messages'); ?></p>
    </header>

    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->street_id; ?>" />

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
                    foreach ($this->designations as $d) {
                        $name  = parent::escape($d);
                        $type  = parent::escape($d->type);
                        $start = $d->start_date ? $d->start_date->format(DATE_FORMAT) : '';
                        $rank  = $d->rank;

                        $input = ($d->type_id == Metadata::TYPE_STREET)
                               ? "<input name=\"designations[{$d->id}]\" value=\"$rank\" type=\"hidden\" />{$d->type_id}"
                               : "<input name=\"designations[{$d->id}]\" value=\"$rank\" type=\"number\" />";

                        echo "
                        <tr><td>$type</td>
                            <td>$name</td>
                            <td>$start</td>
                            <td>$input</td>
                        </tr>
                        ";
                    }
                ?>
                </tbody>
            </table>
            <?php
                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('streets.view', ['id' => $this->street_id]));
            ?>
        </fieldset>
    </form>
</section>

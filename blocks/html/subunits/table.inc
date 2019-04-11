<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->subunits
 */
declare (strict_types=1);
?>
<table>
    <thead>
        <tr><th><?= $this->_('type'); ?></th>
            <th><?= $this->_('identifier'); ?></th>
            <th><?= $this->_('status'); ?></th>
            <th><?= "{$this->_('latitude')},{$this->_('longitude')}"; ?></th>
            <th><?= "{$this->_('x')},{$this->_('y')}"; ?></th>
            <th><?= $this->_('USNG'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($this->subunits as $s) {
            $url = parent::generateUri('subunits.view', ['id'=>$s->id]);
            echo "
            <tr><td><a href=\"$url\">{$s->type_code}</a></td>
                <td><a href=\"$url\">{$s->identifier}</a></td>
                <td>{$s->status}</td>
                <td>{$s->latitude},{$s->longitude}</td>
                <td>{$s->state_plane_x},{$s->state_plane_y}</td>
                <td>{$s->usng}</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>

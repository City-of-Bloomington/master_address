<?php
/**
 * @copyright 2015-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->intersections
 */
?>
<table>
    <thead>
        <tr><th><?= $this->_('name'     ); ?></th>
            <th><?= $this->_('latitude' ); ?></th>
            <th><?= $this->_('longitude'); ?></th>
    </thead>
    <tbody>
    <?php
        foreach ($this->intersections as $i) {
            echo "
            <tr><td>{$i->name}</td>
                <td>{$i->latitude}</td>
                <td>{$i->longitude}</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>

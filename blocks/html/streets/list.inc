<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->streets
 */
?>
<table>
    <thead>
        <tr><th><?= $this->_('id'         ); ?></th>
            <th><?= $this->_('name'       ); ?></th>
            <th><?= $this->_('town'       ); ?></th>
            <th><?= $this->_('status'     ); ?></th>
            <th><?= $this->_('designation'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($this->streets as $street) {
            $name   = parent::escape($street->__toString());
            $status = parent::escape($street->status);
            $town   = parent::escape($street->town_name);
            $type   = parent::escape($street->type_name);
            $uri    = parent::generateUri('streets.view', ['id'=>$street->id]);

            echo "
            <tr><td><a href=\"$uri\">{$street->id}</a></td>
                <td><a href=\"$uri\">$name</a></td>
                <td><a href=\"$uri\">$town</a></td>
                <td><a href=\"$uri\">$status</a></td>
                <td><a href=\"$uri\">$type</a></td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>

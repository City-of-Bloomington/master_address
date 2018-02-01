<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->streets
 */
?>
<table>
    <tbody>
    <?php
        foreach ($this->streets as $street) {
            $name = parent::escape(implode(' ', [
                $street->direction,
                $street->name,
                $street->post_direction,
                $street->suffix_code
            ]));
            $status = parent::escape($street->status);
            $town   = parent::escape($street->town_name);
            $uri    = parent::generateUri('streets.view', ['id'=>$street->id]);

            echo "
            <tr><td><a href=\"$uri\">{$street->id}</a></td>
                <td><a href=\"$uri\">$name</a></td>
                <td><a href=\"$uri\">$town</a></td>
                <td><a href=\"$uri\">$status</a></td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>

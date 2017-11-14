<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->streets
 */
use Application\People\Person;
?>
<table>
    <tbody>
    <?php
        foreach ($this->streets as $street) {
            $streetName = $street->getStreetName();
            $name = $streetName ? parent::escape($streetName->getName()) : '';
            $status = parent::escape($street->getStatus());

            $town   = $street->getTown_id()
                    ? parent::escape($street->getTown()->getName())
                    : '';
            $uri = parent::generateUri('streets.view', ['id'=>$street->getId()]);

            echo "
            <tr><td><a href=\"$uri\">{$street->getId()}</a></td>
                <td><a href=\"$uri\">$name</a></td>
                <td><a href=\"$uri\">$town</a></td>
                <td><a href=\"$uri\">$status</a></td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>

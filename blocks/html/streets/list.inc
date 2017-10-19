<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->streets
 */
use Application\Models\Person;
?>
<section>
    <header>
        <div class="tools">
        <?php
            $userCanEdit = Person::isAllowed('streets', 'update');
            if ($userCanEdit) {
                $h = $this->template->getHelper('buttonLink');
                echo $h->buttonLink(
                    parent::generateUri('streets.update'),
                    $this->_('street_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <tbody>
        <?php
            foreach ($this->streets as $street) {
                $streetName = $street->getStreetName();
                $name = parent::escape($streetName->getName());

                $town   = $street->getTown_id()
                        ? parent::escape($street->getTown()->getName())
                        : '';
                $status = $street->getStatus_id()
                        ? parent::escape($street->getStatus()->getName())
                        : '';

                echo "
                <tr><td>{$street->getId()}</td>
                    <td>$name</td>
                    <td>$town</td>
                    <td>$status</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>

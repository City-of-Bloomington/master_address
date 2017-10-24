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
</section>

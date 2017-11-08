<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->types
 */
declare (strict_types=1);

use Application\People\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['streetType', 'streetTypes', count($this->types)]); ?></h1>
        <div class="tools">
        <?php
            $userCanEdit = Person::isAllowed('streetTypes', 'update');
            if ($userCanEdit) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    parent::generateUri('streetTypes.update'),
                    $this->_('streetType_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <thead>
            <tr><th></th>
                <th><?= $this->_('code'); ?></th>
                <th><?= $this->_('name'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->types as $t) {
                $code = parent::escape($t->getCode());
                $name = parent::escape($t->getName());

                $editButton = '';
                if ($userCanEdit) {
                    $editButton = $helper->buttonLink(
                        parent::generateUri('streetTypes.update', ['id'=>$t->getId()]),
                        $this->_('streetType_edit'),
                        'edit'
                    );
                }
                echo "
                <tr><td>$editButton</td>
                    <td>$code</td>
                    <td>$name</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>

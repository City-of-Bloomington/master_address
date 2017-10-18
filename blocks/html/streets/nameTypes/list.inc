<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->types
 */
declare (strict_types=1);
use Application\Models\Person;

?>
<section>
    <header><h1><?= $this->_(['streetNameType', 'streetNameTypes', count($this->types)]); ?></h1></header>
    <div class="tools">
    <?php
        $userCanEdit = Person::isAllowed('streetNameTypes', 'update');
        if ($userCanEdit) {
            $helper = $this->template->getHelper('buttonLink');
            echo $helper->buttonLink(
                parent::generateUri('streetNameTypes.update'),
                $this->_('streetNameType_add'),
                'add'
            );
        }
    ?>
    </div>
    <table>
        <thead>
            <tr><th></th>
                <th><?= $this->_('name'       ); ?></th>
                <th><?= $this->_('description'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->types as $t) {
                $name = parent::escape($t->getName());
                $code = parent::escape($t->getCode());

                $editButton = '';
                if ($userCanEdit) {
                    $editButton = $helper->buttonLink(
                        parent::generateUri('streetNameTypes.update', ['id'=>$t->getId()]),
                        $this->_('streetNameType_edit'),
                        'edit'
                    );
                }

                echo "
                <tr><td>$editButton</td>
                    <td>$name</td>
                    <td>$code</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>

<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->title
 * @param array  $this->towns
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            $userCanEdit = parent::isAllowed('towns', 'update');
            if ($userCanEdit) {
                echo $helper->buttonLink(
                    parent::generateUri('towns.update'),
                    $this->_('town_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
    <?php
        foreach ($this->towns as $t) {
            $editButton = '';
            if ($userCanEdit) {
                $editButton = $helper->buttonLink(
                    parent::generateUri('towns.update', ['id'=>$t->id]),
                    $this->_('town_edit'),
                    'edit'
                );
            }

            $name = parent::escape($t->name);
            $code = parent::escape($t->code);
            echo "
            <tr><td>$editButton</td>
                <td>$name</td>
                <td>$code</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>

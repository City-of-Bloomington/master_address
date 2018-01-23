<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->title
 * @param array  $this->jurisdictions
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            $userCanEdit = parent::isAllowed('jurisdictions', 'update');
            if ($userCanEdit) {
                echo $helper->buttonLink(
                    parent::generateUri('jurisdictions.update'),
                    $this->_('jurisdiction_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
    <?php
        foreach ($this->jurisdictions as $t) {
            $editButton = '';
            if ($userCanEdit) {
                $editButton = $helper->buttonLink(
                    parent::generateUri('jurisdictions.update', ['id'=>$t->id]),
                    $this->_('jurisdiction_edit'),
                    'edit'
                );
            }

            $name = parent::escape($t->name);
            echo "
            <tr><td>$editButton</td>
                <td>$name</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>

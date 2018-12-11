<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->title
 * @param array  $this->townships
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            $userCanEdit = parent::isAllowed('townships', 'update');
            if ($userCanEdit) {
                echo $helper->buttonLink(
                    parent::generateUri('townships.update'),
                    $this->_('township_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <thead>
            <tr><th></th>
                <th><?= $this->_('name'        ); ?></th>
                <th><?= $this->_('code'        ); ?></th>
                <th><?= $this->_('quarter_code'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->townships as $t) {
                $editButton = '';
                if ($userCanEdit) {
                    $editButton = $helper->buttonLink(
                        parent::generateUri('townships.update', ['id'=>$t->id]),
                        $this->_('township_edit'),
                        'edit'
                    );
                }

                $name    = parent::escape($t->name);
                $code    = parent::escape($t->code);
                $quarter = parent::escape($t->quarter_code);
                echo "
                <tr><td>$editButton</td>
                    <td>$name</td>
                    <td>$code</td>
                    <td>$quarter</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>

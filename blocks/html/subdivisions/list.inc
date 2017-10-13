<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->subdivisions
 */
declare (strict_types=1);
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['subdivision', 'subdivisions', count($this->subdivisions)]); ?></h1>
        <div class="tools">
        <?php
            $userCanEdit = Person::isAllowed('subdivisions', 'update');
            if ($userCanEdit) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    self::generateUri('subdivisions.update'),
                    $this->_('subdivision_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <thead>
            <tr><th><?= $this->_('name'    ); ?></th>
                <th><?= $this->_('township'); ?></th>
                <th><?= $this->_('phase'   ); ?></th>
                <th><?= $this->_('status'  ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($this->subdivisions as $s) {
                $uri      = self::generateUri('subdivisions.view', ['id'=>$s->getId()]);
                $name     = parent::escape($s->getName());
                $status   = parent::escape($s->getStatus());
                $phase    = $s->getPhase();
                $township = $s->getTownship_id()
                          ? parent::escape($s->getTownship()->getName())
                          : '';

                echo "
                <tr><td><a href=\"$uri\">$name</a></td>
                    <td>$township</td>
                    <td>$phase</td>
                    <td>$status</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>

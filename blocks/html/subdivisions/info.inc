<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subdivision $this->subdivision
 */
$fields = ['id', 'name', 'phase', 'status', 'township_name'];
foreach ($fields as $f) {
    $$f = parent::escape($this->subdivision->$f);
}
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools">
        <?php
            if (parent::isAllowed('subdivisions', 'update')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    self::generateUri('subdivisions.update', ['id'=>$id]),
                    $this->_('subdivision_edit'),
                    'edit'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <tr><th><?= $this->_('township'); ?></th><td><?= $township_name; ?></td></tr>
        <tr><th><?= $this->_('phase'   ); ?></th><td><?= $phase;         ?></td></tr>
        <tr><th><?= $this->_('status'  ); ?></th><td><?= $status;        ?></td></tr>
    </table>
</section>

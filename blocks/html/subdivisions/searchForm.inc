<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Subdivisions\Subdivision;
use Application\Townships\TownshipsTable as Townships;

$fields = ['name', 'township_id', 'phase', 'status'];
foreach ($fields as $f) {
    $$f = !empty($_GET[$f]) ? parent::escape($_GET[$f]) : null;
}
?>
<section>
    <header>
        <h1><?= $this->_('subdivision_search'); ?></h1>
    </header>
    <form method="get">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');

            echo $h->field([
                'name'  => 'name',
                'id'    => 'name',
                'label' => $this->_('name'),
                'value' => $name
            ]);

            $options = [['value'=>'']];
            $table   = new Townships();
            $list    = $table->find();
            foreach ($list as $t) {
                $options[] = ['value'=>$t->getId(), 'label'=>parent::escape($t->getName())];
            }
            echo $h->field([
                'name'    => 'township_id',
                'id'      => 'township_id',
                'label'   => $this->_('township'),
                'value'   => $township_id,
                'type'    => 'select',
                'options' => $options
            ]);

            $options = [['value'=>'']];
            foreach (Subdivision::$statuses as $s) { $options[] = ['value' => $s]; }
            echo $h->field([
                'name'    => 'status',
                'id'      => 'status',
                'label'   => $this->_('status'),
                'value'   => $status,
                'type'    => 'select',
                'options' => $options
            ]);

            $options = [['value'=>'']];
            foreach (Subdivision::getPhases() as $p) { $options[] = ['value' => $p]; }
            echo $h->field([
                'name'    => 'phase',
                'id'      => 'phase',
                'label'   => $this->_('phase'),
                'value'   => $phase,
                'type'    => 'select',
                'options' => $options
            ]);
        ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
</section>

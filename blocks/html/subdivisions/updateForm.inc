<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subdivision $this->subdivision
 */
use Application\Models\Subdivision;
use Application\Models\TableGateways\TownshipsTable;

$fields = ['id', 'name', 'township_id', 'phase', 'status'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->subdivision->$get());
}

$title = $id ? $this->_('subdivision_edit') : $this->_('subdivision_add');
?>
<section>
    <header><h1><?= $title; ?></h1></header>
    <form method="post" action="<?= parent::generateUri('subdivisions.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $name,
                    'required' => true
                ]);

                $options = [['value'=>'']];
                $table   = new TownshipsTable();
                $list    = $table->find();
                foreach ($list as $t) { $options[] = ['value'=>$t->getId(), 'label'=>parent::escape($t->getName())]; }
                echo $h->field([
                    'name'    => 'township_id',
                    'id'      => 'township_id',
                    'label'   => $this->_('township'),
                    'value'   => $township_id,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [];
                foreach (Subdivision::$statuses as $s) { $options[] = ['value' => $s]; }
                echo $h->field([
                    'name'     => 'status',
                    'id'       => 'status',
                    'label'    => $this->_('status'),
                    'value'    => $status,
                    'type'     => 'select',
                    'options'  => $options,
                    'required' => true
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

                $h   = $this->template->getHelper('saveAndCancelButtons');
                $uri = $id
                     ? parent::generateUri('subdivisions.view', ['id'=>$id])
                     : parent::generateUri('subdivisions.index');
                echo $h->saveAndCancelButtons($uri);
            ?>
        </fieldset>
    </form>
</section>

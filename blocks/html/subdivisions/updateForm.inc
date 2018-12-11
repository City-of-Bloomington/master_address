<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subdivision $this->subdivision
 * @param array       $this->options
 */
use Application\Subdivisions\Subdivision;
use Application\Townships\TownshipsTable as Townships;

$fields = ['id', 'name', 'township_id', 'phase', 'status'];
foreach ($fields as $f) {
    $$f = parent::escape($this->subdivision->$f);
}
?>
<section>
    <header><h1><?= $this->title; ?></h1></header>
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
                foreach ($this->options['townships'] as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
                echo $h->field([
                    'name'    => 'township_id',
                    'id'      => 'township_id',
                    'label'   => $this->_('township'),
                    'value'   => $township_id,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [];
                foreach ($this->options['statuses'] as $s) { $options[] = ['value' => $s]; }
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
                foreach ($this->options['phases'] as $p) { $options[] = ['value' => $p]; }
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

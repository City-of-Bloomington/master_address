<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Purpose $this->purpose
 */
use Application\Models\Locations\Purpose;

$fields = ['id', 'name', 'type'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->purpose->$get());
}
$title = $id ? $this->_('purpose_edit') : $this->_('purpose_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri('purposes.update'); ?>">
        <fieldset><legend><?= $this->_('purpose'); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'required' => true,
                    'value'    => $name
                ]);

                $options = [['value'=>'']];
                foreach (Purpose::$types as $t) { $options[] = ['value'=>$t]; }
                echo $h->field([
                    'name'     => 'type',
                    'id'       => 'type',
                    'label'    => $this->_('type'),
                    'required' => true,
                    'value'    => $type,
                    'type'     => 'select',
                    'options'  => $options

                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(self::generateUri('purposes.index'));
            ?>
        </fieldset>
    </form>
</section>

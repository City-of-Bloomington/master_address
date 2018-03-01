<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array  $this->directions
 * @param array  $this->types
 * @param string $this->id
 * @param string $this->direction
 * @param string $this->name
 * @param string $this->post_direction
 * @param string $this->suffix_code_id
 * @param string $this->notes

 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('correct'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [['value'=>'']];
                foreach ($this->directions as $name=>$code) { $options[] = ['value' => $code, 'label'=>$name]; }
                echo $h->field([
                    'name'    => 'direction',
                    'id'      => 'direction',
                    'label'   => $this->_('direction'),
                    'value'   => $this->direction,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $this->name,
                    'required' => true
                ]);

                $options = [['value'=>'']];
                foreach ($this->types as $t) { $options[] = ['value' => $t['code'], 'label'=>parent::escape($t['name'])]; }
                echo $h->field([
                    'name'    => 'suffix_code_id',
                    'id'      => 'suffix_code_id',
                    'label'   => $this->_('type'),
                    'value'   => $this->suffix_code_id,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [['value'=>'']];
                foreach ($this->directions as $name=>$code) { $options[] = ['value' => $code, 'label'=>$name]; }
                echo $h->field([
                    'name'    => 'post_direction',
                    'id'      => 'post_direction',
                    'label'   => $this->_('post_direction'),
                    'value'   => $this->post_direction,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $h->field([
                    'name'     => 'notes',
                    'id'       => 'notes',
                    'label'    => $this->_('notes'),
                    'value'    => $this->notes,
                    'type'     => 'textarea'
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('streetNames.view', ['id'=>$this->id]));
            ?>
        </fieldset>
    </form>
</section>

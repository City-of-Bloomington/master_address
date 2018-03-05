<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param int    $this->street_id
 * @param int    $this->name_id
 * @param int    $this->type_id
 * @param int    $this->rank
 * @param string $this->contact_id   Change log contact person
 * @param string $this->contact_name Change log contact person
 * @param string $this->change_notes
 * @param string $this->return_url
 * @param string $this->name          Display string for the current street name
 * @param array  $this->types         Designation type options
*/
use Application\Url;
?>
<section>
    <header><h1><?= $this->_('alias'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->street_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [];
                foreach ($this->types as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
                echo $h->field([
                    'name'     => 'type_id',
                    'id'       => 'type_id',
                    'label'    => $this->_('type'),
                    'value'    => $this->type_id,
                    'type'     => 'select',
                    'options'  => $options,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'name_id',
                    'id'       => 'name_id',
                    'label'    => $this->_('name'),
                    'value'    => $this->name_id,
                    'display'  => $this->name,
                    'type'     => 'chooser',
                    'url'      => new Url(parent::generateUrl('streetNames.index')),
                    'required' => true
                ]);

                echo $h->field([
                    'name'    => 'rank',
                    'id'      => 'rank',
                    'label'   => $this->_('rank'),
                    'value'   => $this->rank,
                    'type'    => 'number'
                ]);

            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons($this->return_url);
            ?>
        </fieldset>
    </form>
</section>

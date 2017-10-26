<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 */
use Application\Models\Person;

$fields = ['firstname','lastname','email', 'phone', 'agency', 'type', 'current', 'notification', 'coordination'];
foreach ($fields as $f) {
	$get = 'get'.ucfirst($f);
	$$f  = self::escape($this->person->$get());
}
$title = $this->person->getId() ? $this->_('person_edit') : $this->_('person_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>

	<form method="post" action="<?= self::generateUri('people.update'); ?>">
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->person->getId(); ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->field(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$firstname, 'required'=>true]);
                echo $field->field(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname'),  'value'=>$lastname,  'required'=>true]);

                echo $field->field(['name'=>'agency',    'id'=>'agency',    'label'=>$this->_('agency'),    'value'=>$agency]);
                echo $field->field(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'),     'value'=>$email ]);
                echo $field->field(['name'=>'phone',     'id'=>'phone',     'label'=>$this->_('phone'),     'value'=>$phone ]);

                $options = [['value'=>'']];
                foreach (Person::getTypes() as $t) { $options[] = ['value'=>$t]; }
                echo $field->field([
                    'name'    => 'type',
                    'id'      => 'type',
                    'label'   => $this->_('type'),
                    'type'    => 'select',
                    'value'   => $type,
                    'options' => $options
                ]);

                $options = [
                    ['value'=>1, 'label'=>$this->_('yes')],
                    ['value'=>0, 'label'=>$this->_('no' )]
                ];
                foreach (['current', 'notification', 'coordination'] as $f) {
                    echo $field->field([
                        'name'   => $f,
                        'id'     => $f,
                        'label'  => $this->_($f),
                        'type'   => 'select',
                        'value'  => $$f,
                        'options'=> $options
                    ]);
                }


				$h = $this->template->getHelper('saveAndCancelButtons');
				echo $h->saveAndCancelButtons(BASE_URI.'/people');
			?>
		</fieldset>
	</form>
</section>

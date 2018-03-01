<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Person $this->person
 * @param string $this->title
 */
use Domain\People\Entities\Person;

$fields = ['firstname','lastname','email', 'phone', 'agency', 'contact_type'];
foreach ($fields as $f) {
    $$f = parent::escape($this->person->$f);
}
foreach (['current', 'notification', 'coordination'] as $f) {
    $$f = $this->person->$f ? 1 : 0;
}
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
    </header>

	<form method="post" action="<?= self::generateUri('people.update'); ?>">
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->person->id; ?>" />
            <?php
                $field = $this->template->getHelper('field');
                echo $field->field(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$firstname, 'required'=>true]);
                echo $field->field(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname'),  'value'=>$lastname,  'required'=>true]);

                echo $field->field(['name'=>'agency',    'id'=>'agency',    'label'=>$this->_('agency'),    'value'=>$agency]);
                echo $field->field(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'),     'value'=>$email, 'type'=>'email']);
                echo $field->field(['name'=>'phone',     'id'=>'phone',     'label'=>$this->_('phone'),     'value'=>$phone, 'type'=>'tel'  ]);

                $options = [['value'=>'']];
                foreach (Person::CONTACT_TYPES() as $t) { $options[] = ['value'=>$t]; }
                echo $field->field([
                    'name'    => 'contact_type',
                    'id'      => 'contact_type',
                    'label'   => $this->_('type'),
                    'type'    => 'select',
                    'value'   => $contact_type,
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

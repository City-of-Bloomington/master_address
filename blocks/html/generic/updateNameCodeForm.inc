<?php
/**
 * Form for editing an object that only has a name field
 *
 * This form is primarily to provide editing for basic lookup tables
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->singular The singular form of the objects' tablename
 * @param string $this->plural   The plural form of the objects' tablename
 * @param Object $this->object   The ActiveRecord object to be edited
 */
$single = $this->singular;
$plural = $this->plural;

$id    = $this->object->getId();
$name  = parent::escape($this->object->getName());
$code  = parent::escape($this->object->getCode());

$size = $this->object->codeSize;

$title = $id ? $this->_("{$single}_edit") : $this->_("{$single}_add");
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri("{$plural}.update"); ?>">
        <fieldset><legend><?= $this->_($single); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'code',
                    'id'       => 'code',
                    'label'    => $this->_('code'),
                    'required' => true,
                    'value'    => $code,
                    'attr'     => ['size'=>$size, 'maxlength'=>$size]
                ]);

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'required' => true,
                    'value'    => $name
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(self::generateUri("{$plural}.index"));
            ?>
        </fieldset>
    </form>
</section>

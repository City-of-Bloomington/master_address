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
                $this->_include('streets/actions/partials/designationFields.inc');
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

<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->direction
 * @param string $this->name
 * @param string $this->post_direction
 * @param string $this->suffix_code_id
 * @param string $this->notes
 * @param array  $this->directions
 * @param array  $this->types
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('streetName_add'); ?></h1></header>
    <form method="post">
        <fieldset>
        <?php
            $this->_include('streets/names/partials/nameFields.inc');

            $h = $this->template->getHelper('saveAndCancelButtons');
            echo $h->saveAndCancelButtons(parent::generateUri('streetNames.index'));
        ?>
        </fieldset>
    </form>
</section>

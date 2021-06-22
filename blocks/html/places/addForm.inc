<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->title
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array $this->categories
 * @param array $this->entities
 * @param array $this->statuses
 * @param array $this->types
 * @param array $this->vicinities
 *
 * All Request object properties are available as template vars and are already escaped
 * @see Domain\Places\Actions\Add\Request
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->title; ?></h1></header>
    <form method="post">
        <fieldset>
            <?php
                $this->_include('places/partials/place_fields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('places.index'));
            ?>
        </fieldset>
    </form>
</section>

<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('subunit_import'); ?></h1>
    </header>

    <form method="post" enctype="multipart/form-data">
        <fieldset>
            <input name="address_id" type="hidden" value="<?= $this->address_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'  => 'csv_file',
                    'id'    => 'csv_file',
                    'label' => 'CSV File',
                    'type'  => 'file',
                    'attr'  => ['accept'=>'text/csv']
                ]);
            ?>
        </fieldset>
    </form>
</section>

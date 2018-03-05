<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param string $this->id
 * @param string $this->notes
 * @param string $this->help        Message to display at the top of the form
 * @param string $this->return_url  Url to use for the cancel button
 */
?>
<section>
    <header>
        <h1><?= $this->_('retire'); ?></h1>
    </header>
    <form method="post">
        <fieldset>
            <input type="hidden" name="id" value="<?= $this->id; ?>" />
            <p><?= $this->help ?></p>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons($this->return_url);
            ?>
        </fieldset>
    </form>
</section>

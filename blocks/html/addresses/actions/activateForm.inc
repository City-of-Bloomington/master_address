<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * @param Address $this->address
 * @param int     $this->address_id
 * @param int     $this->location_id
 *
 * @param int     $this->user_id      User who is doing the data entry
 * @param int     $this->contact_id   Contact ID for the change log
 * @param Person  $this->contact_name Contact person's name
 * @param string  $this->change_notes Change log notes
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('activate'); ?></h1>
    </header>
    <form method="post">
        <fieldset>
            <table>
                <tr><th><?= $this->_('location'); ?></th>
                    <td><?= $this->location_id; ?>
                        <input name="location_id" type="hidden" value="<?= $this->location_id; ?>" />
                    </td>
                </tr>
                <tr><th><?= $this->_('address'); ?></th>
                    <td><?= $this->address; ?>
                        <input name="address_id"  type="hidden" value="<?= $this->address_id;  ?>" />
                    </td>
                </tr>
            </table>

            <?php
                $this->_include('logs/changeLogFields.inc');

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address_id]));
            ?>
        </fieldset>
    </form>
</section>

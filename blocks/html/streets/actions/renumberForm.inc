<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param string $this->title
 * @param int    $this->street_id
 * @param array  $this->addresses
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
        <p><?= $this->_('streets.renumber', 'messages'); ?></p>
    </header>
    <form method="post">
        <fieldset>
            <input name="street_id" type="hidden" value="<?= $this->street_id; ?>" />
            <table>
            <thead>
                <tr><th></th>
                    <th><?= $this->_('current'); ?></th>
                    <th><?= $this->_('street' ); ?></th>
                    <th><?= $this->_('type'   ); ?></th>
                    <th><?= $this->_('status' ); ?></th>
                    <th><?= $this->_('zip'    ); ?></th>
                    <th><?= $this->_('jurisdiction'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($this->addresses as $address_id => $a) {
                    $current      = parent::escape($a['current']->streetNumber());
                    $type         = parent::escape($a['current']->address_type);
                    $street       = parent::escape($a['current']->streetName());
                    $status       = parent::escape($a['current']->status);
                    $zip          = parent::escape($a['current']->zip);
                    $jurisdiction = parent::escape($a['current']->jurisdiction_name);

                    $street_number_prefix = parent::escape($a['updated']->street_number_prefix);
                    $street_number        = parent::escape($a['updated']->street_number);
                    $street_number_suffix = parent::escape($a['updated']->street_number_suffix);

                    echo "
                    <tr><td><input name=\"addresses[$address_id][street_number_prefix]\"
                                  value=\"$street_number_prefix\"
                                  class=\"street_number_prefix\"/>
                            <input name=\"addresses[$address_id][street_number]\"
                                  value=\"$street_number\" type=\"number\" />
                            <input name=\"addresses[$address_id][street_number_suffix]\"
                                  value=\"$street_number_suffix\"
                                  class=\"street_number_suffix\" />
                        </td>
                        <td>$current</td>
                        <td>$street</td>
                        <td>$type</td>
                        <td>$status</td>
                        <td>$zip</td>
                        <td>$jurisdiction</td>
                    </tr>
                    ";
                }
            ?>
            </tbody>
            </table>
            <?php
                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('streets.view', ['id'=>$this->street_id]));
            ?>
        </fieldset>
    </form>
</section>

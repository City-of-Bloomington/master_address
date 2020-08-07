<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Address $this->address
 * @param array   $this->data            Raw subunit data from the CSV file
 * @param array   $this->statuses        List of valid subunit statuses
 * @param array   $this->types           List of valid subunit types
 * @param array   $this->location_types  List of valid location types
 * @param array   $this->errors          Errors indexed by csv line number
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->address; ?></h1>
        <h2><?= $this->_('subunit_import'); ?></h2>
    </header>

    <form method="post" enctype="multipart/form-data">
        <fieldset>
            <input name="address_id" type="hidden" value="<?= $this->address->id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'  => 'csv_file',
                    'id'    => 'csv_file',
                    'label' => 'CSV File',
                    'type'  => 'file',
                    'attr'  => ['accept'=>'text/csv']
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address->id]));
            ?>
        </fieldset>
    </form>
    <?php
        if ($this->data) {
            echo "
            <table>
            ";
            foreach ($this->data as $i=>$r) {
                $class = isset($this->errors[$i]) ? 'class="error"' : '';
                echo "<tr $class>";
                foreach ($r as $f) {
                    echo "<td>$f</td>";
                }
                echo "</tr>";
            }
            echo "
            </table>
            ";
        }
    ?>
    <section>
        <p>This will import subunits from a CSV file.
           Here is an empty <a href="<?= BASE_URI; ?>/templates/subunits.csv">CSV template</a> file you can start from.
           The CSV file must have a header row.
           The fields must be in this order:</p>
        <table>
            <tbody>
                <tr><th>Status</th>
                    <td><ul><?php foreach ($this->statuses as $s) { echo "<li>$s</li>"; } ?></ul></td>
                </tr>
                <tr><th>Subunit Type</th>
                    <td><ul><?php foreach ($this->types as $t) { echo "<li>$t[code]</li>"; }?></ul></td>
                </tr>
                <tr><th>Identifier</th>
                    <td></td>
                </tr>
                <tr><th>Notes</th>
                    <td></td>
                </tr>
                <tr><th>Location Type</th>
                    <td><ul><?php foreach ($this->location_types as $t) { echo "<li>$t[name]</li>"; } ?></ul></td>
                </tr>
                <tr><th>Mailable</th>
                    <td>True: y, Y, 1.  Anything else is false, including empty space</td>
                </tr>
                <tr><th>Occupiable</th>
                    <td>True: y, Y, 1.  Anything else is false, including empty space</td>
                </tr>
                <tr><th>Group Quarter</th>
                    <td>True: y, Y, 1.  Anything else is false, including empty space</td>
                </tr>
                <tr><th>Contact ID</th>
                    <td>Must be a user_id number</td>
                </tr>
                <tr><th>Change Log Notes</th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </section>
</section>

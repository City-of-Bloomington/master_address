<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Street  $this->street
 * @param array   $this->data            Raw address data from the CSV file
 * @param array   $this->statuses        List of valid address status names
 * @param array   $this->types           List of valid address types
 * @param array   $this->jurisdictions   List of valid jurisdiction names
 * @param array   $this->townships       List of valid township names
 * @param array   $this->location_types  List of valid location types
 * @param array   $this->quarterSections List of valid quarter sections
 * @param array   $this->zipCodes        List of valid zip codes
 * @param array   $this->errors          Errors indexed by csv line number
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('address_import'); ?></h1>
    </header>

    <form method="post" enctype="multipart/form-data">
        <fieldset>
            <input name="street_id" type="hidden" value="<?= $this->street->id; ?>" />
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
                echo $h->saveAndCancelButtons(parent::generateUri('streets.view', ['id'=>$this->street->id]));
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
        <p>This will import addresses from a CSV file.
           Here is an <a href="<?= BASE_URI; ?>/templates/addresses.xlsx">MS Excel template</a>
           you can start from as well as an empty
           <a href="<?= BASE_URI; ?>/templates/addresses.csv">CSV template</a> file.
           The Excel template has dropdowns with valid data values, but you must remember
           to save your data as CSV before uploading the file here.
           The CSV file you upload must have a header row, with fields in this order:
       </p>
        <table>
            <tbody>
                <tr><th>Status</th>
                    <td><ul><?php foreach ($this->statuses as $s) { echo "<li>$s</li>"; } ?></ul></td>
                </tr>
                <tr><th>Prefix</th>
                    <td></td>
                </tr>
                <tr><th>Number</th>
                    <td></td>
                </tr>
                <tr><th>Suffix</th>
                    <td></td>
                </tr>
                <tr><th>Address 2</th>
                    <td></td>
                </tr>
                <tr><th>Address Type</th>
                    <td><ul><?php foreach ($this->types as $t) { echo "<li>$t</li>"; } ?></ul></td>
                </tr>
                <tr><th>Jurisdiction</th>
                    <td><ul><?php foreach ($this->jurisdictions as $j) { echo "<li>$j[name]</li>"; } ?></ul></td>
                </tr>
                <tr><th>Township</th>
                    <td><ul><?php foreach ($this->townships as $t) { echo "<li>$t[name]</li>"; } ?></ul></td>
                </tr>
                <tr><th>Subdivision ID</th>
                    <td>Must be a <a href="<?= parent::generateUrl('subdivisions.index'); ?>">Subdivion ID</a> number</td>
                </tr>
                <tr><th>Plat</th>
                    <td>Must be a <a href="<?= parent::generateUri('plats.index'); ?>">Plat ID</a> number</td>
                </tr>
                <tr><th>Lot</th>
                    <td></td>
                </tr>
                <tr><th>Section</th>
                    <td></td>
                </tr>
                <tr><th>Quarter Section</th>
                    <td><ul><?php foreach ($this->quarterSections as $s) { echo "<li>$s</li>"; } ?></ul></td>
                </tr>
                <tr><th>Zip</th>
                    <td>Must be a valid zip code number.
                        <ul><?php foreach ($this->zipCodes as $z) { echo "<li>$z[zip]: $z[city]</li>"; } ?></ul>
                    </td>
                </tr>
                <tr><th>Zipplus4</th>
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
                    <td>Must be a <a href="<?= parent::generateUri('people.index'); ?>">Person ID</a> number</td>
                </tr>
                <tr><th>Change Log Notes</th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </section>
</section>

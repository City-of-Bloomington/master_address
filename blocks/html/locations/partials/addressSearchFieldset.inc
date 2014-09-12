<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$address = !empty($_REQUEST['address']) ? View::escape($_REQUEST['address']) : '';
?>
<fieldset><legend>Street Address</legend>
    <div>
        <label  for="address">Street Address</label>
        <input name="address" id="address" value="<?php echo $address; ?>" />
    </div>
    <button type="submit" class="search">Search</search>
</fieldset>

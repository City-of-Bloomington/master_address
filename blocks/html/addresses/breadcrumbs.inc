<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address optional
 * @param Street  $this->street  optional
 */

if ($this->address) {
    $address = parent::escape($this->address);
    $street_id = $this->address->street_id;
    $street  = parent::escape($this->address->streetName());
}
elseif ($this->street) {
    $street_id = $this->street->id;
    $street = parent::escape($this->street);
}
?>
<nav class="breadcrumbs">
	<a href="<?= parent::generateUri('streets.index'); ?>">Streets</a>
    <?php
        if (isset($street)) {
            $uri = parent::generateUri('streets.view', ['id'=>$street_id]);
            echo "<a href=\"$uri\">$street</a>";
        }

        if (isset($address)) {
            $number = $this->address->streetNumber();
            $uri = parent::generateUri('addresses.view', ['id'=>$this->address->id]);
            echo "<a href=\"$uri\">$number</a>";
        }
    ?>
</nav>

<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Address $this->address	You must pass either an address
 * @param Subunit $this->subunit	or a subunit
 */
if (isset($this->subunit)) {
	$address = $this->subunit->getAddress();
}
else {
	$address = $this->address;
}
?>
<nav class="breadcrumbs">
    <a href="<?php echo BASE_URL; ?>/addresses">Addresses</a>
    <?php
        $name = View::escape($address->getStreetName());
        echo "<a href=\"{$address->getURL()}\">$name</a>";
        if (isset($address)) {
            echo "<a href=\"{$address->getURL()}\">{$address->getStreet_number()}</a>";
        }
    ?>
</nav>

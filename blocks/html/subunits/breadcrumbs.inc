<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
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
<div class="breadcrumbs">
	<ul><li><a href="<?php echo BASE_URL; ?>/addresses">Addresses</a></li>
		<?php
			$name = View::escape($address->getStreetName());
			echo "<li><a href=\"{$address->getURL()}\">$name</a></li>";
			if (isset($address)) {
				echo "
				<li><a href=\"{$address->getURL()}\">{$address->getStreet_number()}</a></li>
				";
			}
		?>
	</ul>
</div>
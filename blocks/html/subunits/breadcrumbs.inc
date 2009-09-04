<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address optional
 * @param Street $this->street optional
 * 
 */
$name="";
if(isset($this->subunit)){
	$address = $this->subunit->getAddress();
}
else if(isset($this->address)){
	$address = $this->address;
	
}
$street = $address->getStreet();	

?>
<div class="breadcrumbs">
	<ul><li><a href="<?php echo BASE_URL; ?>/addresses">Addresses</a></li>
		<?php
			$name = View::escape($street->getStreetName());
			echo "<li><a href=\"{$address->getURL()}\">$name</a></li>";
			if(isset($address)){
				echo "
				<li>{$address->getStreet_number()}</li>
				";
			}
		?>
	</ul>
</div>
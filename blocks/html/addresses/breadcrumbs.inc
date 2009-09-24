<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address optional
 * @param Street $this->street optional
 * @param string $this->action (optional)
 */
$name="";

if(isset($this->street)){
	$street = $this->street;
}
elseif (isset($this->address)) {
	$street = $this->address->getStreet();
}
?>
<div class="breadcrumbs">
	<ul><li><a href="<?php echo BASE_URL; ?>/streets">Streets</a></li>
		<?php
			if (isset($street)) {
				$name = View::escape($street->getStreetName());
				echo "<li><a href=\"{$street->getURL()}\">$name</a></li>";
			}

			if (isset($this->address)) {
				$number = $this->address->getStreet_number();
				if ($number) {
					echo "<li><a href=\"{$this->address->getURL()}\">$number</a></li>";
				}
			}

			if (isset($this->action)) {
				$action = View::escape(ucfirst($this->action));
				echo "<li>$action</li>";
			}
		?>
	</ul>
</div>
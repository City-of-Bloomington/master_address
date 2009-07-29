<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<div class="breadcrumbs">
	<ul><li><a href="<?php echo BASE_URL; ?>/streets">Streets</a></li>
		<?php
			$name = View::escape($this->address->getStreet()->getStreetName());
			echo "
			<li><a href=\"{$this->address->getStreet()->getURL()}\">$name</a></li>
			<li>{$this->address->getStreet_number()}</li>
			";
		?>
	</ul>
</div>
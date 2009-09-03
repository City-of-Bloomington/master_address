<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street (optional)
 * @param string $this->action (optional)
 */
?>
<div class="breadcrumbs">
	<ul><li><a href="<?php echo BASE_URL; ?>/streets">Streets</a></li>
		<?php
			if (isset($this->street)) {
				$name = View::escape($this->street->getStreetName());
				echo "<li><a href=\"{$this->street->getURL()}\">$name</a></li>";
			}
			if (isset($this->action)) {
				$action = View::escape(ucfirst($this->action));
				echo "<li>$action</li>";
			}
		?>
	</ul>
</div>
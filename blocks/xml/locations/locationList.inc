<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param LocationList $this->locationList
 */
?>
<locations>
	<?php
		foreach ($this->locationList as $location) {
			$name = View::escape($location->getCommon_name());
			$active = $location->isActive() ? 'true' : 'false';
			$mailable = $location->isMailable() ? 'true' : 'false';
			$livable = $location->isLivable() ? 'true' : 'false';
			echo "
			<location lid=\"{$location->getLid()}\"
					active=\"$active\" mailable=\"$mailable\" livable=\"$livable\">
				<location_id>{$location->getLocation_id()}</location_id>
				<type>{$location->getType()}</type>
				<address id=\"{$location->getStreet_address_id()}\">
					{$location->getAddress()->getStreetAddress()}
				</address>
				<common_name>$name</common_name>
			</location>
			";
		}
	?>
</locations>
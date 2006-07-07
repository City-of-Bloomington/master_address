<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
<p>Here's the list of things in the system, and the base actions I know I need to support.
I'll try and add in the completion dates and keep them accurate.</p>

	<table style="border:1px solid black;">
	<tr><th></th><th>find</th><th>view</th><th>add</th><th>update</th><th>delete</th></tr>
	<tr><td>addresses</td><td>2006-05-26</td><td>2006-06-07</td><td>In progress</td><td></td><td></td></tr>
	<tr><td>annexations</td><td></td><td></td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>buildingTypes</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>buildings</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>cities</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>directions</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>districtTypes</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>district_places</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>districts</td><td></td><td>In progress</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>jurisdictions</td><td>2006-05-10</td><td></td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>names</td><td>2006-06-07</td><td>2006-06-07</td><td>2006-06-20</td><td>2006-05-10</td><td></td></tr>
	<tr><td>placeHistory</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>placeTypes</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>places</td><td>2006-05-23</td><td>2006-06-07</td><td>2006-05-23</td><td></td><td></td></tr>
	<tr><td>platTypes</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>plats</td><td></td><td></td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>recyclingPickupWeeks</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>roles</td><td></td><td></td><td>2006-04-28</td><td>2006-04-28</td><td>2006-04-28</td></tr>
	<tr><td>route_streets</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>routes</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>segments</td><td>In progress</td><td></td><td></td><td></td><td></td></tr>
	<tr><td>stateRoads</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>statuses</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>streetNameTypes</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>streetNames</td><td>2006-06-20</td><td></td><td></td><td></td><td></td></tr>
	<tr><td>streets</td><td>2006-06-07</td><td>2006-06-07</td><td></td><td></td><td></td></tr>
	<tr><td>subdivisionNames</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>subdivision_plats</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>subdivisions</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>suffixes</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>towns</td><td>2006-05-10</td><td></td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>townships</td><td>2006-05-10</td><td></td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>trashPickupDays</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>unitTypes</td><td>2006-05-10</td><td>2006-05-10</td><td></td><td>2006-05-10</td><td></td></tr>
	<tr><td>units</td><td></td><td></td><td></td><td></td><td></td></tr>
	<tr><td>user_roles</td><td></td><td></td><td>2006-04-28</td><td>2006-04-28</td><td>2006-04-28</td></tr>
	<tr><td>users</td><td></td><td></td><td>2006-04-28</td><td>2006-04-28</td><td>2006-04-28</td></tr>
	</table>

	<table style="border:1px solid black;">
	<tr><th></th><th>list</th><th>assign</th><th>remove</th></tr>
	<tr><td>routes have streets</td><td></td><td></td><td></td></tr>
	<tr><td>streets are on routes</td><td></td><td></td><td></td></tr>
	<tr><td>names are used as streetNames</td><td>2006-06-07</td><td>2006-07-07</td><td></td></tr>
	<tr><td>streets have streetNames</td><td>2006-06-07</td><td>2006-07-07</td><td></td></tr>
	<tr><td>streets have addresses</td><td>2006-06-14</td><td></td><td></td></tr>
	<tr><td>streets have segments</td><td>2006-06-07</td><td>2006-07-07</td><td></td></tr>
	<tr><td>segments have addresses</td><td>2006-06-16</td><td>2006-07-07</td><td></td></tr>
	<tr><td>places have a history of actions</td><td></td><td></td><td></td></tr>
	<tr><td>places have addresses</td><td>2006-06-07</td><td>2006-07-07</td><td></td></tr>
	<tr><td>places have buildings</td><td>2006-06-16</td><td></td><td></td></tr>
	<tr><td>places have units</td><td></td><td></td><td></td></tr>
	<tr><td>places can be in many districts</td><td></td><td></td><td></td></tr>
	<tr><td>districts have places</td><td></td><td></td><td></td></tr>
	<tr><td>buildings can be in many places</td><td></td><td></td><td></td></tr>
	<tr><td>buildings have subunits</td><td></td><td></td><td></td></tr>
	<tr><td>plats encompass many places</td><td></td><td></td><td></td></tr>
	<tr><td>subdivisions are made up of many plats</td><td></td><td></td><td></td></tr>
	</table>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
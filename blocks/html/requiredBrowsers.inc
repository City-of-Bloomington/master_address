<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$browser = get_browser($_SERVER['HTTP_USER_AGENT'],true);
?>
<h1>Required Browsers</h1>
<p>You're using <?php echo "$browser[browser] $browser[version]"; ?></p>
<p>These are the minimum versions of browsers required for this application.</p>
<table>
	<thead>
		<tr><th>Browser</th>
			<th>Version</th>
		</tr>
	</thead>
	<tbody>
		<tr><th>Internet Explorer</th>
			<td>8</td>
		</tr>
		<tr><th>Mozilla Firefox</th>
			<td>3</td>
		</tr>
		<tr><th>Apple Safari</th>
			<td>4</td>
		</tr>
	</tbody>
</table>
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Direction</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Direction Info</legend>
		<table>

			<tr><td><label for="direction-direction_code">direction_code</label></td>
				<td><input name="direction[direction_code]" id="direction-direction_code" />
				</td>
			</tr>
				
			<tr><td><label for="direction-description">description</label></td>
				<td><input name="direction[description]" id="direction-description" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>
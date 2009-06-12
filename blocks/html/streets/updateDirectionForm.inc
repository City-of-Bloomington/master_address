<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Direction $this->direction
 */
?>
<h1>Update Direction</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Direction Info</legend>
		<input name="id" type="hidden" value="<?php echo $this->direction->getId(); ?>" />
		<table>
   		     <tr><td><label for="direction-code">Code</label></td>
   		          <td><input name="direction[direction_code]" id="direction-code" value="<?php echo $this->direction->getCode(); ?>" />
                 </td>
            </tr>
			<tr><td><label for="direction-description">description</label></td>
				<td><input name="direction[description]" id="direction-description" value="<?php echo View::escape($this->direction->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>

<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov> 
 */
$directions = new DirectionList();
$directions->find();
$suffixes = new SuffixList();
$suffixes->find();
$types = new StreetNameTypeList();
$types->find();
?>
<h1>Update Street Name</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Name Info</legend>
		<input name="id" type="hidden" value="<?php echo $this->streetName->getId(); ?>" />
   		<input name="streetName[street_id]" type="hidden" value="<?php echo $this->streetName->getStreet_id(); ?>" />
		<table>
  			<tr><td><label for="streetName-street_direction_code">Direction</label></td>
				<td><select name="streetName[street_direction_code]" id="streetName-street_direction_code" >
                       <option></option>
                       <?php
                         foreach($directions as $direction){
	                         $selected = ($direction->getCode() == $this->streetName->getStreet_direction_code())?"selected=\"selected\"":"";
	                         echo "<option $selected value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>"; 
                         }
                       ?>
                     </select>
				</td>
			</tr>

			<tr><td><label for="streetName-street_name">Street Name</label></td>
				<td><input name="streetName[street_name]" id="streetName-street_name" value="<?php echo $this->streetName->getStreet_name(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="streetName-street_type_suffix_code">Street Type</label></td>
				<td><select name="streetName[street_type_suffix_code]" id="streetName-street_type_suffix_code">
                       <option></option>
                       <?php
  
                       foreach($suffixes as $suffix){
	                       $selected = ($suffix->getCode() == $this->streetName->getStreet_type_suffix_code())?"selected=\"selected\"":"";
	                       echo "<option $selected value=\"{$suffix->getCode()}\">$suffix</option>";
	                   }
                       ?>
                    </select>
				</td>
			</tr>
			<tr><td><label for="streetName-post_direction_suffix_code">Post Direction</label></td>
				<td><select name="streetName[post_direction_suffix_code]" id="streetName-post_direction_suffix_code" >
                       <option></option>
                       <?php
                         foreach($directions as $direction){
	                         $selected = ($direction->getCode() == $this->streetName->getPost_direction_suffix_code())?"selected=\"selected\"":"";
	                         echo "<option $selected value=\"{$direction->getCode()}\">{$direction->getDescription()}</option>"; 
                         }
                       ?>
                     </select>
				</td>
			</tr>
			<tr><td><label for="streetName-street_name_type">Street Name Type</label></td>
				<td><select name="streetName[street_name_type]" id="streetName-street_name_type" >
                       <option></option>
                       <?php
                         foreach($types as $type){
	                         $selected = ($type->getType() == $this->streetName->getStreet_name_type())?"selected=\"selected\"":"";
	                         echo "<option $selected value=\"{$type->getType()}\">$type</option>"; 
                         }
                       ?>
                     </select>
				</td>
			</tr>
			<tr><td><label for="streetName-effective_start_date">Effective Start Date</label></td>
				<td><input name="streetName[effective_start_date]" id="streetName-effective_start_date" value="<?php echo $this->streetName->getEffective_start_date(); ?>" />
				</td>
			</tr>
			<tr><td><label for="streetName-effective_end_date">Effective End Date</label></td>
				<td><input name="streetName[effective_end_date]" id="streetName-effective_end_date" value="<?php echo $this->streetName->getEffective_end_date(); ?>" />
				</td>
			</tr>
					 
			<tr><td><label for="streetName-notes">Notes</label></td>
				<td><input name="streetName[notes]" id="streetName-notes" value="<?php echo $this->streetName->getNotes(); ?>" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>
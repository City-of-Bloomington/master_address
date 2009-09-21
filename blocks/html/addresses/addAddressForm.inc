<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param Address $this->address
 * @param Location $this->location
 * @param array $this->locationData
 */
?>
<h1>Add Address</h1>

<form method="post" action="<?php echo BASE_URL; ?>/addresses/addAddress.php"
	onsubmit="checkRequiredFields(this); return false;">
<!--								
	onsubmit="checkRequiredFields(this);FRAMEWORK.getChangeLog(this,'addAddressForm'); return false;">
	-->
								
	<table>
	<tr><td><fieldset>
				<table>
					<tr><td>Action</td>
						<td><label>
								<input type="radio" name="action" value="add"
									onclick="document.getElementById('changeLogEntry-action').value='add';" />
								Add
							</label>
							<label>
								<input type="radio" name="action" value="assign" checked="checked"
									onclick="document.getElementById('changeLogEntry-action').value='assign';" />
								Assign
							</label>
						</td>
					</tr>
					<tr><td><label>Street</label></td>
						<td><?php
								$block = new Block('addresses/partials/streetSelector.inc',
													array('street'=>$this->address->getStreet()));
								echo $block->render('html');
							?>
						</td>
					</tr>
					<tr><td><label for="street_number">Number</label></td>
						<td><input name="street_number" id="street_number" /></td>
					</tr>
				</table>
			</fieldset>

			<fieldset>
				<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc'; ?>
			</fieldset>
		</td>
		<td><fieldset>
				<?php
					if (isset($this->location)) {
						echo "<input type=\"hidden\" name=\"location_id\" value=\"{$this->location->getId()}\" />";
						$this->locationData = $this->location->getUpdatableData($this->address);
					}
					include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
				?>
			</fieldset>
		</td>
	</tr>
	</table>
	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',
						   array('action'=>'assign','id'=>'addAddressForm'));
		echo $block->render('html');
	?>
	<fieldset>
		<button type="submit" class="submit">Save</button>
		<label>
			<input type="checkbox" name="batch_mode" />
			Submit in Batch Mode
		</label>
	</fieldset>
</form>

<script type="text/javascript">
function checkRequiredFields(form)
{
	var elements = document.getElementsByTagName("label");
	for(var i=0;i<elements.length;i++){
		if(elements[i].className && elements[i].className =='required'){
			var id = elements[i].getAttribute('for');
			alert('id '+id);
			var obj = document.getElementById(id);
			alert(' type '+obj.type);
			switch(obj.type){
				case 'text':
					if(obj.value.length() == 0){
						alert(id+" is required");
						obj.focus();
						return false;
					}
				break;				
				case 'select-one':
					if(obj.options[obj.selectedIndex].value == ""){
						alert(id+" is required");
						obj.focus();
						return false;
					}
				break;	
			}
		}
	}
	return true;	
}
</script>

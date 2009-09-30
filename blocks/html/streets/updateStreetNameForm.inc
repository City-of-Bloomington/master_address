<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param StreetName $this->streetName
 */
$streetNames = $this->streetName->getStreet()->getNames();
?>
<div class="interfaceBox">
	<h1>Street Names</h1>
	<form method="post" action="<?php echo BASE_URL; ?>/streets/updateStreetName.php">
		<input type="hidden" name="streetName_id" value="<?php echo $this->streetName->getId(); ?>" />
		<table>
			<tr><th>Type</th>
				<th>Name</th>
				<th>Start</th>
				<th>End</th>
				<th></th>
			</tr>
			<?php
				foreach ($streetNames as $streetName) {
					if ($streetName->getId()==$this->streetName->getId()) {
						echo "
						<tr><td>{$streetName->getStreetNameType()}</td>
							<td><select name=\"street_direction_code\" id=\"street_direction_code\">
									<option></option>
						";
										$directions = new DirectionList();
										$directions->find();
										foreach ($directions as $direction) {
											$selected = $this->streetName->getStreet_direction_code()==$direction->getCode()
														? 'selected="selected"'
														:'';
											echo "
											<option value=\"{$direction->getCode()}\" $selected>
												{$direction->getCode()}
											</option>
											";
										}
								$name = View::escape($this->streetName->getStreet_name());
						echo "
								</select>
								<input name=\"street_name\" id=\"street_name\" value=\"$name\" />
								<select name=\"street_type_suffix_code\" id=\"street_type_suffix_code\">
									<option></option>
						";
										$types = new StreetTypeList();
										$types->find();
										foreach ($types as $type) {
											$selected = $this->streetName->getStreet_type_suffix_code()==$type->getCode()
														? 'selected="selected"'
														: '';
											echo "
											<option value=\"{$type->getCode()}\" $selected>
												{$type->getCode()}
											</option>
											";
										}
						echo "
								</select>
								<select name=\"post_direction_suffix_code\" id=\"post_direction_suffix_code\">
									<option></option>
						";
										foreach ($directions as $direction) {
											$selected = $this->streetName->getPost_direction_suffix_code()==$direction->getCode()
														? 'selected="selected"'
														: '';
											echo "
											<option value=\"{$direction->getCode()}\" $selected>
												{$direction->getCode()}
											</option>
											";
										}
						echo "
								</select>
							</td>
							<td>{$streetName->getStartDate('n/j/Y')}</td>
							<td>{$streetName->getEndDate('n/j/Y')}</td>
							<td><button type=\"submit\" class=\"save\">Save</button></td>
						</tr>
						";
					}
					else {
						echo "
						<tr>
							<td>{$streetName->getStreetNameType()}</td>
							<td>$name</td>
							<td>{$streetName->getStartDate('n/j/Y')}</td>
							<td>{$streetName->getEndDate('n/j/Y')}</td>
							<td></td>
						</tr>
						";
					}
				}
			?>

		</table>
	</form>
</div>

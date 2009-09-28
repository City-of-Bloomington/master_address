<table>
	<tr><td><label for="street_name_type">Street Name Type</label></td>
		<td><select name="street_name_type" id="street_name_type">
			<?php
				$list = new StreetNameTypeList();
				$list->find();
				foreach ($list as $type) {
					// You should never choose the type: "STREET" directly.
					if ($type->getType()!='STREET') {
						echo "<option>{$type->getType()}</option>";
					}
				}
			?>
			</select>
		</td>
	</tr>
</table>
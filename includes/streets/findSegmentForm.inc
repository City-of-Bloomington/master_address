<?php
/*
	Required Variables:		street

	$_GET variables:	name[]
*/
?>
<h2>Find Segments</h2>
<form id="findSegmentForm" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Name info</legend>
	<input name="street_id" type="hidden" value="<?php echo $street->getId(); ?>" />
	<table>
	<tr><td><label for="name-name">Street Name</label></td>
		<td><select name="name[direction_id]" id="name-direction_id"><option></option>
			<?php
				$directionList = new DirectionList();
				$directionList->find();
				foreach($directionList as $direction)
				{
					if (isset($_GET['name']['direction_id']) && $_GET['name']['direction_id']==$direction->getId()) { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
			<input name="name[name]" id="name-name" value="<?php if(isset($_GET['name']['name'])) echo $_GET['name']['name']; ?>" />
			<select name="name[suffix_id]" id="name-suffix_id"><option></option>
			<?php
				$list = new SuffixList();
				$list->find();
				foreach($list as $suffix)
				{
					if (isset($_GET['name']['suffix_id']) && $_GET['name']['suffix_id']==$suffix->getId()) { echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
					else { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
				}
			?>
			</select>
			<select name="name[postDirection_id]" id="name-postDirection_id"><option></option>
			<?php
				foreach($directionList as $direction)
				{
					if (isset($_GET['name']['postDirection_id']) && $_GET['name']['postDirection_id']==$direction->getId()) { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="name-town_id">Town</label></td>
		<td><select name="name[town_id]" id="name-town_id"><option></option>
			<?php
				$towns = new TownList();
				$towns->find();
				foreach($towns as $town)
				{
					if (isset($_GET['name']['town_id']) && $_GET['name']['town_id']==$town->getId()) { echo "<option value\"{$town->getId()}\" selected=\"selected\">{$town->getSegment()}</option>"; }
					else { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	</table>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>
<?php
	if (isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
            #--------------------------------------------------------------------------
            # Find any segments matching their information
            #--------------------------------------------------------------------------
            $streets = new StreetList($search);
            if (count($streets))
            {
                echo "
                <h2>Segments Found</h2>
                <form method=\"post\" action=\"".BASE_URL."/streets/addSegments.php\">
                <fieldset><legend>Choose Segments To Add</legend>
                    <input name=\"street_id\" type=\"hidden\" value=\"{$street->getId()}\" />
                    <ul>
                ";
                    foreach($streets as $s)
                    {
                        echo "<li>{$s->getFullStreetName()}<ul>";
                        $list = new SegmentList(array("street_id"=>$s->getId()));
                        foreach($list as $segment)
                        {
                            echo "
                            <li><table>
                                <tr><td><input name=\"segments[{$segment->getId()}]\" id=\"segment{$segment->getId()}\" type=\"checkbox\" /></td>
                                    <td><label for=\"segment{$segment->getId()}\">{$segment->getTag()}</label></td>
                                    <td>{$segment->getStartingNumber()}</td>
                                    <td>{$segment->getEndingNumber()}</td>
                                </tr>
                                </table>
                            </li>
                            ";
                        }
                        echo "</ul></li>";
                    }
                echo "
                    </ul>

                    <button type=\"submit\" class=\"submit\">Submit</button>
                </fieldset>
                </form>
                ";
            }
             else { echo "<p>No Segments Found</p>"; }
        }
	}
?>
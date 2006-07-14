<?php
/*
	Required Variables:		street

	$_GET variables:	name[]
*/
?>
<h2>Find Segments</h2>
<form id="findSegmentForm" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>
<fieldset><legend>Search</legend>
	<input name="street_id" type="hidden" value="<?php echo $street->getId(); ?>" />
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
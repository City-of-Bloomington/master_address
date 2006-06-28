<?php
/*
	Required Variables:		return_url
*/
?>
<h2>Find a Street</h2>
<form id="findStreetForm" action="javascript:findStreet();">
<fieldset><legend>Name</legend>
	<table>
	<tr><td><label for="name-name">Name</label></td>
		<td><select name="name[direction_id]" id="name-direction_id"><option></option>
			<?php
				$directionList = new DirectionList($PDO);
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
				$list = new SuffixList($PDO);
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
				$towns = new TownList($PDO);
				$towns->find();
				foreach($towns as $town)
				{
					if (isset($_GET['name']['town_id']) && $_GET['name']['town_id']==$town->getId()) { echo "<option value\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
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
<div id="findStreetResults"></div>
<script type="text/javascript">
	var findStreetRequest = getXMLHttpRequestObject();

	function findStreet()
	{
		var params = getFormValues(document.getElementById("findStreetForm"));
		findStreetRequest.open("get","<?php echo BASE_URL; ?>/streets/findStreet.php?"+params);
		findStreetRequest.onreadystatechange = findStreetResults;
		findStreetRequest.send(null);
	}

	function findStreetResults()
	{
		// Clear out the results div, so we can update it's contents
		var div = document.getElementById("findStreetResults");
		while(div.firstChild) { div.removeChild(div.firstChild); }

		if (findStreetRequest.readyState == 4)
		{
			// Build a new table from the results
			var results = eval("("+findStreetRequest.responseText+")");
			if (results)
			{
				tbody = document.createElement("tbody");
				for(var i=0; i<results.length; i++)
				{
					var id = document.createTextNode(results[i].id);
					var name = document.createTextNode(results[i].name);

					var tr = document.createElement("tr");

					var td = document.createElement("td");
					var a = document.createElement("a");
					a.setAttribute("href","<?php echo $return_url; ?>"+results[i].id);
					a.appendChild(id);
					td.appendChild(a);
					tr.appendChild(td);

					td = document.createElement("td");
					a.removeChild(a.firstChild);
					a.appendChild(name);
					td.appendChild(a);
					tr.appendChild(td);

					tbody.appendChild(tr);
				}
				table = document.createElement("table");
				table.appendChild(tbody);
				div.appendChild(table);
			}
			else
			{
				var p = document.createElement("p");
				p.appendChild(document.createTextNode("No Streets Found"));
				div.appendChild(p);
			}
		}
	}
</script>
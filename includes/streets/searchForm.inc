<?php
/*
	Required Variables:		return_url
*/
?>
<h2>Find a Street</h2>
<form id="findStreetForm" action="javascript:findStreet();">
<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>
<fieldset><legend>Search</legend>
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
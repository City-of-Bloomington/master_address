<?php
/*
	Required Variables:		return_url
*/
?>
<h2>Find a name</h2>
<form id="findNameForm" action="javascript:findName();">
<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="search">Search</button>
</fieldset>
</form>
<div id="findNameResults"></div>
<script type="text/javascript">
	var findNameRequest = getXMLHttpRequestObject();

	function findName()
	{
		var params = getFormValues(document.getElementById("findNameForm"));
		findNameRequest.open("get","<?php echo BASE_URL; ?>/names/findName.php?"+params);
		findNameRequest.onreadystatechange = findNameResults;
		findNameRequest.send(null);
	}

	function findNameResults()
	{
		// Clear out the results div, so we can update it's contents
		var div = document.getElementById("findNameResults");
		while(div.firstChild) { div.removeChild(div.firstChild); }

		if (findNameRequest.readyState == 4)
		{
			// Build a new table from the results
			var results = eval("("+findNameRequest.responseText+")");
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
				p.appendChild(document.createTextNode("No Names Found"));
				div.appendChild(p);
			}
		}
	}
</script>
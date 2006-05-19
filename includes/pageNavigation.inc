<?php
/*
	Required variables:		$pages

	$_GET variables:	page
*/
	$maxNumLinks = 10;
	$url = "$_SERVER[PHP_SELF]";

	$query = preg_replace("/page=(\d+)(;?)/","",$_SERVER['QUERY_STRING']);
	if ($query) { if ($query{strlen($query)-1} != ";") $query.=";"; }
?>
<div class="pageNavigation">
<?php
	if ($_GET['page'] != 0)
	{
		$previous = $_GET['page']-1;
		echo "<button type=\"button\" class=\"back\" onclick=\"document.location.href='{$url}?{$query}page=$previous';\">Back</button>";
	}
	foreach ($pages as $i=>$index)
	{
		if ($i != $_GET['page']) { echo "<a href=\"{$url}?{$query}page=$i\">$i</a>"; }
		else { echo "<a href=\"{$url}?{$query}page=$i\" class=\"current\">$i</a>"; }
	}
	if ($_GET['page'] != $pages->getLastIndex())
	{
		$next = $_GET['page']+1;
		echo "<button type=\"button\" class=\"next\" onclick=\"document.location.href='{$url}?{$query}page=$next'\">Next</button>";
	}
?>
</div>
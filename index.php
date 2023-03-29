<?php

require_once("functions.php");

require_once("header.php");

echo '
<h1>Data Storage Finder</h1>
<p>Welcome to the Data Storage Finder. Answer the questions below (left) to view the appropriate service(s) based on your selected criteria. Then click one or more tiles below (right) to see detailed information about each service. If you would like to see all services listed on a single page, <a style="color: green;" href="all_services.php">click here</a>.</p>
<table class="table table-striped table-condensed">
	<tr>
		<td height="1000px">
		<br>
';

echo '<iframe width="500px" src="navframe.php" id="Iframe" title="Navigation Menu" frameborder="0" height="100%">';
echo '</iframe>';

echo '
		</td>
		<td width="100%" valign="top">
';

populateResults();
				
echo '
		</td>
	</tr>
</table>
';

echo '
<script>
	function removeComparison() {
		$(".flex-container").remove();
		$(".flip").css("color","green");
		$(".flip").css("pointer-events", "auto");
	}
</script>

<div class="panel" style="display: none;">
	
</div>
';
	
require_once("footer.php");

?>
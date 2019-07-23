<!DOCTYPE html>
<html lang="en">
<head>
	<title>PDF</title>
</head>
<body>
	<table width="100%">
		<?php
			for ($i=0; $i < 10; $i++) { 
				echo "<tr>";
				echo '<td><img src="'.$barcode.'" /></td>';
				echo '<td><img src="'.$barcode.'" /></td>';
				echo '<td><img src="'.$barcode.'" /></td>';
				echo '<td><img src="'.$barcode.'" /></td>';
				echo '<td><img src="'.$barcode.'" /></td>';
				echo "</tr>";
			}
		?>          
	</table>
</body>
</html>
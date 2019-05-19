<?php
	include_once "config.php";  //include database. Use $conn.
	
	$choice = $conn->real_escape_string($_GET['choice']);
	
	$query = "SELECT * FROM mail_template WHERE ID='$choice'";
	
	$result = $conn->query($query);
	while ($row = $result->fetch_assoc())
	{
				echo $row{'TEMPLATE'};
	}
?>
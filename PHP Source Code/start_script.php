<?php
	$servername = 'localhost';	//dont chanage
	$username = 'php';	//username for the application
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';		//should never need to be changed
	
	$userId = "Null";	//passed from joomla to this application
	$storeNo = "Null";	// passed from joomla to this application
	$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	//establish connection with sql database
	}

	if(isset($_GET['storeNo'])){
		$storeNo = $_GET['storeNo'];	//gets the store no passed from joomla
	}//end storeno
	if(isset($_GET['userId'])){
		$userId = $_GET['userId'];		//gets the employee id passed from joomla
	}//end userid
	

	echo("<html><h1>NAME HERE</h1>");	//html consturct
	
	
	$sql = "SQL HERE";
	$result = $conn->query($sql);	//complicated sql statement 
	//gets the sum of items in the managers store by item name, and figures out if the manager is below the trigger point
	
	//builds the table
	echo("
			<table border ='1'>
			<tr>
				<th>ROWNAME 1</th>
				<th>ROWNAME N</th>
			</tr>
	");


	while ($row = $result->fetch_assoc()) {	
			echo("
				<tr>
					<td>{$row['DATA FOR ROWNAME 1']}</td>
					<td>{$row['DATA FOR ROWNAME N']}</td>					
				</tr>
			");
	

	}
	echo("</table></html>");//html closer
	$conn->close();	//sql closer
?>
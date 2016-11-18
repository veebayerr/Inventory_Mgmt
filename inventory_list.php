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
	

	echo("<html>
		<head><script type='text/javascript' src='js/inventoryList.js'></script></head>
		<body bgcolor='#ffffff'><h1>Items Below Trigger Point</h1>");	//html construct
	
	$sql = "SELECT * FROM item_inventory i1, items i2 where i2.item_no = i1.item_no and i1.store_no = '{$storeNo}'";
	$result = $conn->query($sql);	//complicated sql statement 
	//gets the sum of items in the managers store by item name, and figures out if the manager is below the trigger point
	
	//builds the table
	echo("
			<div style='display: inline; float: left;'>
			<table border ='1' style='border-collapse: collapse'>
			<tr>
				<th>Stock_no</th>
				<th>Item Name</th>
				<th>Item Quantity</th>
				<th>Expiration Date</th>
			</tr>
	");


	while ($row = $result->fetch_assoc()) {	
			echo("
				<tr>
					<td ondblclick='loadInfo()'>{$row['no']}</td>
					<td ondblclick='loadInfo()'>{$row['item_name']}</td>
					<td ondblclick='loadInfo()'>{$row['item_quantity']}</td>
					<td ondblclick='loadInfo()'>{$row['expiration_date']}</td>
				</tr>
				</div>
			");
	}

	echo("
		<div id='itemInfo' style='display: inline; float: right; visibility: hidden; margin-left: 100px;'>
			Item Name: <input type='text'><br>
			Barcode: <input type='text'><br>
			Quantity: <input type='number'><br>
			Expiration Date: <input type='date'><br>
			Price: <input type='number'><br>

			Requested for Removal? <input type='text'><br>
		</div>
	");
	echo("</table></body></html>");//html closer
	$conn->close();	//sql closer
?>
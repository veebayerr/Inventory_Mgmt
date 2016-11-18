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
	
	<h1> Manager Create New Item </h1>");	//html consturct
	
	if(isset($_POST['Create_Item'])){
		$filtered_item_name = filter_var($_POST['item_name'], FILTER_SANITIZE_STRING);
		$filtered_item_producer = filter_var($_POST['item_producer'], FILTER_SANITIZE_STRING);
		$sql = "INSERT INTO `items` (`item_no`, `barcode`, `item_name`, `item_producer`) VALUES (NULL, '{$_POST['barcode']}', '{$filtered_item_name}', '{$filtered_item_producer}')";
		$conn->query($sql);
		echo("Inserted");
	}
	
	
	
	echo("
		<form action='' method='post'>
			<input type ='number' name='barcode' min = '0' required> Barcode <br>
			<input type = 'text' name = 'item_name' required'> Item Name <br>
			<input type = 'text' name = 'item_producer' required> Item Producer <br>
			<button type='submit' name='Create_Item'> Submit </button>
		</form>
	"
	);
	
	
	
	
	//$sql = "INSERT INTO `items` (`item_no`, `barcode`, `item_name`, `item_producer`) VALUES (NULL, '12345', '12345', '12345')";
	//$result = $conn->query($sql);	//complicated sql statement 
	//gets the sum of items in the managers store by item name, and figures out if the manager is below the trigger point
	

	echo("</body>");//html closer
	$conn->close();	//sql closer
?>


<?php
	$servername = 'localhost';
	$username = 'php';
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';
	$userId = "Null";	//passed from joomla to this application
	$storeNo = "Null";	// same 
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
	

	echo("<html><h1>Items Below Trigger Point</h1>");	//html consturct
	
	//if the manager wants to update the trigger point on an item
	if(isset($_POST['Update_Trigger_Point'])){
		$sql = "UPDATE `trigger_point` SET `trigger_point` = '{$_POST['trigger']}' WHERE `trigger_point`.`no` = {$_POST['no']}";
		$conn->query($sql);	//run query
		echo("Updated<br>");	//inform update and continue to load page
	}
	
	
	
	$sql = "SELECT sum(i1.item_quantity),(i1.item_no), (t1.trigger_point), (i2.item_name), (i2.barcode), (i1.item_sale_price), (i1.item_discount_percent), (t1.no)  FROM item_inventory i1, trigger_point t1, items i2 WHERE i1.store_no ='{$storeNo}' and t1.store_no='{$storeNo}' and i1.item_no = t1.item_no and i2.item_no = i1.item_no group by i1.item_no";
	$result = $conn->query($sql);	//complicated sql statement 
	//gets the sum of items in the managers store by item name, and figures out if the manager is below the trigger point
	
	

	//builds the table
	echo("
			<table border ='1'>
			<tr>

			</tr>
	");


	while ($row = $result->fetch_assoc()) {	
		$inStock = $row['sum(i1.item_quantity)'];	//get qty of item in stock
		$trigger_point = $row['trigger_point'];	//get the trigger point
		$percent = 1.0 - $row['item_discount_percent']; //calculates the sale price		
		$salePrice = $row['item_sale_price'] * $percent;
		
		if($inStock < $trigger_point){	//if an item is below trigger point put it on the list
			echo("
				<tr>

					<td>{$row['item_name']} </td>
					<td>| In stock: {$inStock} </td>
					<td>| Trigger Point: {$trigger_point}</td>
				</tr>
			");
		}

	}
	echo("</table></html>");//html closer
	$conn->close();	//sql closer
	?>
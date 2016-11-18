<?php

	$servername = 'localhost';
	$username = 'php';
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';
	$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli
	$userId = "Null";	//passed from joomla to this application
	$storeNo = "Null";	// same 
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	//establish connection with sql database
	}

	echo("<html>");	//html construct
	
	if(isset($_GET['storeNo'])){
		$storeNo = $_GET['storeNo'];	//gets the store no passed from joomla
	}//end storeno
	if(isset($_GET['userId'])){
		$userId = $_GET['userId'];		//gets the employee id passed from joomla
	}//end userid
  
	if(isset($_POST['Delete_Item'])){	//if requested to delete an item run this
		$sql = "UPDATE `item_inventory` SET `needs_removal` = '1' WHERE `item_inventory`.`no` = {$_POST['Delete_Item']} ;";	//flag an item for an inventory worker to remove
		$conn->query($sql);
		echo("This has been added to the inventory workers queue ".$_POST['Delete_Item'].".<br>");
	}
	if(isset($_POST['Update_Percent'])){
		$fix = $_POST['percent'];	//updates the discount percent if the manager wants to put a expired item on sale
		$sql = "UPDATE `item_inventory` SET `item_discount_percent` = '{$fix}' WHERE `item_inventory`.`no` = {$_POST['no']};";
		$conn->query($sql);
		echo("Updated");
	}
	
	
		$sql = "SELECT * FROM item_inventory i1, items i2 WHERE i1.item_no = i2.item_no and `expiration_date` < now() and `store_no` = {$storeNo} and `item_quantity` > 0 and needs_removal = 0";
		$result = $conn->query($sql);	//select items from the store that are expired
		
		//table construct here
		echo("
		<h1>Expired Items</h1>
		
		<table border ='1'>
			<tr>
				<th>Stock No</th>
				<th>Barcode</th>
				<th>Item name</th>
				<th>expiration date</th>
				<th>item quantity</th>
				<th>Discount Percent</th>
				<th>Sale Price</th>
				<th>Remove From Inventory</th>
			</tr>
		
		
		");
		
		while ($row = $result->fetch_assoc()) {	
				$percent = 1.0 - $row['item_discount_percent']; 	//fix the percent discount	
				$salePrice = $row['item_sale_price'] * $percent;	//show the sale price with discount
				$fix = $row['item_discount_percent'];	//possibly can be removed check later
				
				//cant comment in echo
				
				//prints the information about the item, and allows the manager to change certain things about the item
			echo("
			<tr>
				<td>{$row['no']}</td>
				<td>{$row['barcode']}</td>
				<td>{$row['item_name']}</td>
				<td>{$row['expiration_date']}</td>
				<td>{$row['item_quantity']}</td>
				<td>
					<form action='' method='post'>
						
						<input min = '0.00' max = '1.00' maxlength='3' type='number' step = '0.01' name='percent' size='3'  value = '{$row['item_discount_percent']}'>
						<input type = 'hidden' name = 'no' value = '{$row["no"]}'>
						<input type = 'submit' name = 'Update_Percent'>
				
				</td>
				<td>{$salePrice}</td>
				<td>
					<form action='' method='post'>
						<button type='submit' name='Delete_Item' Value = '{$row["no"]}'>
						Remove
						</button>
					</form>
				</td>
				
			</tr>
			"); //end echo
		}
		echo("</table></html>"); //html closer
		$conn->close();	//sql closer

?>
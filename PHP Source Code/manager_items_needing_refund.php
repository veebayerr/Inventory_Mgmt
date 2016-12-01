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
  
	if(isset($_POST['Approve'])){	//if requested to delete an item run this
		$sql = "UPDATE `item_sold` SET `needs_refund` = '-1' WHERE `item_sold`.`sale_no` = {$_POST['sale_no']}";
		$conn->query($sql);
	}
	if(isset($_POST['Deny'])){	//if requested to delete an item run this

		$sale_no = $_POST['sale_no'];
		$item_no = getItemNo($sale_no,$conn);
		$qty_in_stock = getQty($item_no, $conn);
		$qty_in_stock = $qty_in_stock + $_POST['qty'];
		$sql = "UPDATE `item_inventory` SET `item_quantity` = '{$qty_in_stock}' WHERE `item_inventory`.`no` = '{$item_no}';";		//if manager denies refund adjust the inventory
		$conn->query($sql);
		$sql = "DELETE FROM `item_sold` WHERE `item_sold`.`sale_no` = {$_POST['sale_no']}";
		$conn->query($sql);
		$sql = "DELETE FROM `transaction` WHERE `transaction`.`transaction_no` = {$_POST['transaction_no']}";
		$conn->query($sql);
	
	}//end deny
	
		
		$sql = "select * from item_sold i1, item_inventory i2, items i3 where i1.item_no = i2.item_no and i1.needs_refund ='1' and i1.store_no ='{$storeNo}' and i2.item_no = i3.item_no";
		$result = $conn->query($sql);	//select items from the store that are expired
		
		//table construct here
		echo("
		<h1>Items Requested for Refund</h1>
		
		<table border ='1'>
			<tr>
				<th>Item Name</th>
				<th>Transaction No</th>
				<th>Sale No</th>
				<th>Sale Price</th>
				<th>Quantity Sold</th>
				<th>Approve</th>
				<th>Deny</th>
			</tr>
		<form action='' method='post'>
		
		");
		
		while ($row = $result->fetch_assoc()) {	

					//cant comment in echo
			
				//prints the information about the item, and allows the manager to change certain things about the item
			echo("
			<tr>
				<form action='' method='post'>
					<input type = 'hidden' name = 'sale_no' value = {$row['sale_no']}> 
					<td>{$row['item_name']}</td>
					<td>{$row['transaction_no']}</td>
					<td>{$row['sale_no']}</td>
					<td>{$row['sale_price']}</td>
					<td>{$row['quantity_sold']}</td>
					<td>
						<input type = 'submit' name = 'Approve' value ='Approve'>
					</td>
					<td>
						<input type = 'submit' name = 'Deny' value ='Deny'>
						<input type = 'hidden' name = 'qty' value = '{$row['quantity_sold']}'>
						<input type = 'hidden' name = 'transaction_no' value = '{$row['transaction_no']}'>
					</td>
				
				
			</tr>
			</form>
			"); //end echo
		}
		echo("</table></html>"); //html closer
		$conn->close();	//sql closer
		
	function getQty($bcd, $conn){
		$sql = "SELECT `item_quantity` from item_inventory where item_no = {$bcd}";	//working
		$res = $conn->query($sql);
		$qty_in_stock = 0;
		while($row = $res->fetch_assoc()){
			$qty_in_stock = $row['item_quantity'];
		}
				
		return($qty_in_stock);	//returns qty in stock from a stock pile
		
	}//end get qty
	
	
	function getItemNo($sale_no,$conn){
		$sql = "SELECT `item_no` FROM `item_sold` WHERE `sale_no` ='{$sale_no}'";	//working
		$res = $conn->query($sql);
		$item_no = 0;
		while($row = $res->fetch_assoc()){
			$item_no = $row['item_no'];
		}
				
		return($item_no);	//returns the item number given the sale number
	}

?>
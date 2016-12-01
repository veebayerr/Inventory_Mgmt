<?php

$servername = 'localhost';
$username = 'php';
$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
$dbname = 'joomla';
$userId = "Null";	//passed from joomla to this application
$storeNo = "Null";	// same 
$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli

	if(isset($_GET['storeNo'])){
		$storeNo = $_GET['storeNo'];	//gets the store no passed from joomla
	}//end storeno
	if(isset($_GET['userId'])){
		$userId = $_GET['userId'];		//gets the employee id passed from joomla
	}//end userid
	


	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	//establish connection with sql database
	}
	
	echo("<html>");	//html construct
		
		
		
	//cant comment inside echo
	//builds a submit form
	//builds the table
	echo("
		
		<h1>Manage Inventory</h1>
		<form action='' method='post'>
						<input type='number' name='barcode' size='10'> Scan Inventory No
						<input type = 'submit' name = 'Single_Item'>
						</button>
		</form>
		
		<form action ='' method = 'post'>
		<input type = 'submit' name = 'update_multi_item' value = 'Save'>
				
		<table border ='1'>
			<tr>
				<th>Stock No</th>
				<th>Barcode</th>
				<th>item quantity</th>
				<th>Item name</th>
				<th>Before Discount</th>
				<th>Discount</th>
				<th>Discount Percent</th>
				<th>expiration date</th>
				<th>active</th>
			</tr>
		");
		

	
	
	if(isset($_POST['update_multi_item'])){
		
		$i= $_POST['size'] -1 ;		
		while($i>=0){	
			$slp = 	$_POST['item_sale_price_'.$i];
			$qty = $_POST['item_quantity_'.$i];
			$idp = $_POST['item_discount_percent_'.$i];
			$act = $_POST['active_'.$i];
			$bcd = $_POST['barcode_'.$i];
			$sql = "UPDATE `item_inventory` SET `item_sale_price` = '{$slp}', `item_quantity` = '{$qty}', `item_discount_percent` = '{$idp}', `active` = '{$act}' WHERE `item_inventory`.`no` = {$bcd}";	
			$conn->query($sql);
			$i--;
		}
	}	
	
	if(isset($_POST['update_single_item'])){
			$sql = "UPDATE `item_inventory` SET `item_sale_price` = '{$_POST['item_sale_price']}', `item_quantity` = '{$_POST['item_quantity']}', `item_discount_percent` = '{$_POST['item_discount_percent']}', `active` = '{$_POST['active']}' WHERE `item_inventory`.`no` = {$_POST['barcode']}";	
			$conn->query($sql);
	}
	
	if(isset($_POST['Single_Item'])){
		
		$sql = "SELECT * FROM item_inventory i1, items i2 WHERE i1.no = '{$_POST['barcode']}' and i1.item_no = i2.item_no";
		$result = $conn->query($sql);
		
		
		while ($row = $result->fetch_assoc()) {
				$salePrice = (1- ($row['item_discount_percent'])) *	$row['item_sale_price'];	//calculate the sale price
				/*
					Cant comment inside of an echo
					
					this builds a large table that allows the manager to edit items inside of the inventory
					in the single item version it will also keep the manager at the item he was last modifiying, and gives him a button to return to the main inventory for his store
				*/
				echo("
				<form action ='' method ='post'>
				
					<input type = 'hidden' name = 'barcode' value = '{$row['no']}'>
					<input type = 'hidden' name = 'update_single_item' value ='1'>
					<input type = 'hidden' name = 'Single_Item' >
				<tr>
					<td>{$row['no']}</td>
					<td>{$row['barcode']}</td>
					
					<td>
							<input min = '0' max = '9999' maxlength = '4'  type='number' name='item_quantity'   value = '{$row['item_quantity']}'>
					</td>
					
					<td>{$row['item_name']}</td>
					
					
					<td>
						<input  min = '0.00' max='9999.99' maxlength = '6' type='number' step = '0.01' name='item_sale_price'   value = '{$row['item_sale_price']}'>
					</td>
					
					<td>{$salePrice}</td>
					
					<td>
						<input min = '0.00' max = '1.00' maxlength='3' type='number' step = '0.01' name='item_discount_percent'   value = '{$row['item_discount_percent']}'>
					</td>
					
					<td>{$row['expiration_date']}</td>
					
					<td>
						<input min = '0' max = '1' maxlength='1' type='number' step = '1' name='active'   value = '{$row['active']}'>
					</td>
							
					</form>
				</tr>
				
				");//end echo
			
			
		}//end while
		
		
		
		
		//return to the multi-item list
		echo("
			<form action='' method='post'>
				Return
				<input type ='submit' name 'nothing'>
			</form>
		");
		
	}else{	//end single item   
	//start multi item


	
	
	
	
		$sql = "SELECT * FROM item_inventory i1, items i2 WHERE i1.active = '1' and i1.item_no = i2.item_no and i1.store_no='{$storeNo}'";
		$result = $conn->query($sql);	//finds all items that are in the managers store and are active
		
		

		
		$i = 0;
		echo("
		
		<input type = 'hidden' name = 'update_multi_item' value ='1'>
		");
		while ($row = $result->fetch_assoc()) {
				$salePrice = (1- ($row['item_discount_percent'])) *	$row['item_sale_price'];	//calculate sale price
				
				/*
					Cant comment inside an echo
					fills in the table from sql
					shows all items that are active and in the managers store
				*/
				
				echo("
	
				
					<input type = 'hidden' name = 'barcode_{$i}' value = '{$row['no']}'>
					
				<tr>
					<td>{$row['no']}</td>
					<td>{$row['barcode']}</td>
					
					<td>
							<input min = '0' max = '9999' maxlength = '4'  type='number' name='item_quantity_{$i}'   value = '{$row['item_quantity']}'>
					</td>
					
					<td>{$row['item_name']}</td>
					
					
					<td>
						<input  min = '0.00' max='9999.99' maxlength = '6' type='number' step = '0.01' name='item_sale_price_{$i}'   value = '{$row['item_sale_price']}'>
					</td>
					
					<td>{$salePrice}</td>
					
					<td>
						<input min = '0.00' max = '1.00' maxlength='3' type='number' step = '0.01' name='item_discount_percent_{$i}'   value = '{$row['item_discount_percent']}'>
					</td>
					
					<td>{$row['expiration_date']}</td>
					
					<td>
						<input min = '0' max = '1' maxlength='1' type='number' step = '1' name='active_{$i}'   value = '{$row['active']}'>
					</td>
					
			
				</tr>
				
				");//end echo
			$i++;
		
		}
	echo("<input type = 'hidden' name ='size' value = {$i}></form>");
	}//end else	
	echo("</table></html>"); //html closer
	$conn->close();	//sql closer

?>
<?php
	$servername = 'localhost';
	$username = 'php';
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';
	$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli
	$userId = "Null";	//passed from joomla to this application
	$storeNo = "Null";	// same
	
		
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error); //if there is an error connectiong with the database
	}
		
		
		
			
	echo("<html><h1>Items Requested for Removal</h1>");	//basic html opener

			
	if(isset($_GET['storeNo'])){
		$storeNo = $_GET['storeNo'];	//gets the store no passed from joomla
	}//end storeno
	if(isset($_GET['userId'])){
		$userId = $_GET['userId'];		//gets the employee id passed from joomla
	}//end userid
	
	if(isset($_POST['Scan_Barcode'])){	// when the user scans in a barcode search for the item on the flagged for removal list
		$sql = "SELECT * FROM item_inventory i1, items i2 where i1.item_no = i2.item_no and i1.needs_removal = '1' and i1.store_no ='{$storeNo}' group by i1.no";
		$result = $conn->query($sql);	//run the query
		$removed = 0;	//boolean to see if anything is done
		
		while ($row = $result->fetch_assoc()) {	
		
			if($row['no'] == $_POST['barcode'] ){	//if the scanned barcode is on the list do this
					$removed =1;	//set the removed flag
					$sql = "UPDATE `item_inventory` SET `item_quantity` = '0', `needs_removal` = '0' WHERE `item_inventory`.`no` = {$_POST['barcode']}";
					$conn->query($sql);	//run the above query (removes the items from inventory)
			}
		}
		
		if($removed==1){
			echo("Item removed<br>");//print message to user
		}else{
			echo("Item not on list<br>");
		}
		
	}//end Scan Barcode
			
			
			
			
			
			
	//cant comment below until the echo ends
	
	//sets up form action for scanning a barcode 

	//prints the table header
	echo("
		<form action='' method='post'>
						<input type='number' name='barcode' size='10'> Scan Inventory No
						<input type = 'submit' name = 'Scan_Barcode'>
						</button>
		</form>
		
		
		<table border ='1'>
			<tr>
				<th>Inventory No</th>
				<th>barcode</th>
				<th>Item Name</th>
				<th>Item Quantity</th>
				
			</tr>
			
	"); //end echo
		
		
		$sql = "SELECT * FROM item_inventory i1, items i2 where i1.item_no = i2.item_no and i1.needs_removal = '1' and i1.store_no ='{$storeNo}' group by i1.no";
		$result = $conn->query($sql);	//runs the above query
		
		
		
		while ($row = $result->fetch_assoc()) {	
			//prints the information for the table pulled from a sql query
			echo("
				<tr>
					<td>{$row['no']}</td>
					<td>{$row['barcode']}</td>
					<td>{$row['item_name']}</td>
					<td>{$row['item_quantity']}</td>
				</tr>
				
			");	//end echo	
		}//end while
		
		echo("</table></html>"); //html closer
		$conn->close();	//sql closer
	?>
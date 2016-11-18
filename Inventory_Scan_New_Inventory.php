<?php
	$servername = 'localhost';	//dont chanage
	$username = 'php';	//username for the application
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';		//should never need to be changed
	
	$userId = "Null";	//passed from joomla to this application
	$storeNo = "Null";	// passed from joomla to this application
	$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli
	$load = 1;
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	//establish connection with sql database
	}

	if(isset($_GET['storeNo'])){
		$storeNo = $_GET['storeNo'];	//gets the store no passed from joomla
	}//end storeno
	if(isset($_GET['userId'])){
		$userId = $_GET['userId'];		//gets the employee id passed from joomla
	}//end userid

	if(isset($_POST['Search'])){
		$load=0;
		$sql = "SELECT * FROM `items` WHERE barcode ='{$_POST['barcode']}'";
		$result = $conn->query($sql);
		$var = 0;
			while ($row = $result->fetch_assoc()) {	
			
				echo("
					<form action ='' method ='post'>
						<input type ='hidden' name = 'item_no' value = '{$row['item_no']}'>
						Item Name : {$row['item_name']} <br>
						<input type = 'date' name = 'expiration_date' required >Exp Date<br>
						<input  min = '0.00' max='9999.99'  type='number' step = '0.01' name='item_sale_price'  required value = ''>Item Sale Price <br>
						<input type = 'number' min ='0' max = '99999' name = 'item_quantity' value ='' required >Item Quantity <br>
						<input type = 'submit' name = 'Insert' value ='Insert Into Inventory' >
					</form>
				
				
				");
			
				$var++;
			}
			
			if($var==0){
				echo("<h1>Error item not found, please consult manager.</h1> <br>");
			}
		
	}
	if(isset($_POST['Insert'])){
		$load=0; //makes the scan barcode function not load
		$sql = "INSERT INTO `item_inventory` (`no`, `item_no`, `store_no`, `expiration_date`, `taxable`, `item_cost`, `item_sale_price`, `item_quantity`, `item_discount_percent`, `needs_removal`, `active`) VALUES (NULL, '{$_POST['item_no']}', '{$storeNo}', '{$_POST['expiration_date']}', 'True', '0', '{$_POST['item_sale_price']}', '{$_POST['item_quantity']}', '0', '0', '1');";
		$result = $conn->query($sql);	
		echo("<img alt='TESTING' src = 'http://groupfive.ddns.net/Scripts/barcode.php?codetype=Code39&size=40&text={$conn->insert_id}&print=true'> "); //generates a barcode 
	}

if($load==1){	
	echo("
		<html><h1>Scan Inventory In</h1>	
		<form action ='' method ='post'>
			<input min = '0'  maxlength='48' type='number' step = '1' name='barcode' value =''>
			<input type = 'submit' name = 'Search' value ='Search'>
		</form>
		
	");
}

	echo("</table></html>");//html closer
	$conn->close();	//sql closer
?>
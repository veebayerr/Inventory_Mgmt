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
	

	echo("<html><h1>Items Sold Report</h1>");	//html consturct
	
	
	//$sql = "SELECT sum(payment_ammount) FROM `payment` WHERE payment_date >= '2016-11-09' and payment_date <= '2016-11-11' and payment_type = 'cash'";
	//$result = $conn->query($sql);	//complicated sql statement 
	
	if(isset($_GET['Submit'])){
		$sql = "SELECT  sum(i1.quantity_sold), i1.item_no, i2.item_name, sum(i1.sale_price) FROM item_sold i1, items i2 WHERE i1.item_no = i2.item_no and i1.store_no = '{$storeNo}' and i1.date >= '{$_GET['start_date']}' and i1.date <= '{$_GET['end_date']}' group by i1.item_no";
		$result = $conn->query($sql);
		echo("
		<table border ='1'>
			<tr>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		");
		
		$sum = 0;
		$total_sold = 0;
		while ($row = $result->fetch_assoc()) {
			$sum = $sum + $row['sum(i1.quantity_sold)'];
			$total_sold = $total_sold + $row['sum(i1.sale_price)'];
			echo("
			<tr>
				<td>{$row['item_name']} </td>
				<td>| Qty Sold: {$row['sum(i1.quantity_sold)']} </td>
				<td>| Profit: {$row['sum(i1.sale_price)']} </td>
			
			</tr>
			
			");
			
		}
		
			
		echo("
		
		</table>");
		
	}

	
	

	echo("</table></html>");//html closer
	$conn->close();	//sql closer
?>
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
	

	echo("<html><h1>Sales Report</h1>");	//html consturct
	
	
	//$sql = "SELECT sum(payment_ammount) FROM `payment` WHERE payment_date >= '2016-11-09' and payment_date <= '2016-11-11' and payment_type = 'cash'";
	//$result = $conn->query($sql);	//complicated sql statement 
	
	if(isset($_GET['Submit'])){
		$sql = "SELECT sum(payment_ammount) FROM `payment` WHERE payment_date >= '{$_GET['start_date']}' and payment_date <= '{$_GET['end_date']}' and store_no = {$storeNo} and payment_type = 'cash'";
		$cash = $conn->query($sql);
		$sql = "SELECT sum(payment_ammount) FROM `payment` WHERE payment_date >= '{$_GET['start_date']}' and payment_date <= '{$_GET['end_date']}' and store_no = {$storeNo} and payment_type = 'credit'";
		$credit = $conn->query($sql);
		$sql = "SELECT sum(payment_ammount) FROM `payment` WHERE payment_date >= '{$_GET['start_date']}' and payment_date <= '{$_GET['end_date']}' and store_no = {$storeNo} and payment_type = 'check'";
		$check = $conn->query($sql);
		$check = $conn->query($sql);
		$cashval = 0;
		$paid_total = 0;
		$sql = "SELECT sum(`price_before_tax`) FROM `transaction` WHERE `store_no` ='{$storeNo}' and `transaction_date` <='{$_GET['end_date']}' and `transaction_date` >='{$_GET['start_date']}'";

		$tval = $conn->query($sql);
		while ($row = $cash->fetch_assoc()) {
				$cashval = $row['sum(payment_ammount)'];
		}
		while ($row = $credit->fetch_assoc()) {
				$creditval = $row['sum(payment_ammount)'];
		}
		while ($row = $check->fetch_assoc()) {
				$checkval = $row['sum(payment_ammount)'];
		}
		while ($row = $tval->fetch_assoc()) {
				$paid_total = $row['sum(`price_before_tax`)'];
		}
		
		
		
		$change = ($cashval + $creditval + $checkval) - $paid_total;
		
		echo("$".$cashval." in sales paid with cash.<br>");		
		echo("$".$creditval." in sales paid with credit.<br>");		
		echo("$".$checkval." in sales paid with check.<br>");	
		echo("$".($cashval + $creditval + $checkval)." total sales.<br>");
		echo("$". $change . " total change given out.<br>");
		echo("$". $paid_total . " profit made.<br>");
		
		
	}
	
	

	echo("</table></html>");//html closer
	$conn->close();	//sql closer
?>
<?php

	$servername = 'localhost';
	$username = 'php';
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';
	$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli
	$userId = "Null";	//passed from joomla to this application
	$storeNo = "Null";	// same 
	$startCash = 190.00;
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
  
	if(isset($_POST['Submit'])){
		echo("Closed shift " . $_POST['shift_no'] . ".<br>");
		$sql = "UPDATE `register` SET `is_open` = '0' WHERE `register`.`register_no` = {$_POST['register_no']};";
		$conn->query($sql);
		$sql = "UPDATE `register_shift` SET `shift_open` = '0' WHERE `register_shift`.`shift_no` = {$_POST['shift_no']};";
		$conn->query($sql);
		$date = date("Y-m-d");
		$sql = "UPDATE `register_shift` SET `close_date` = '{$date}' WHERE `register_shift`.`shift_no` = {$_POST['shift_no']}";
		$conn->query($sql);
		
		
		
	}
	
	
		
		
		
		
		
		//table construct here
		echo("
		<h1>Close Register</h1>
			
			<table border ='1'>
			<tr>
				<th>Register No</th>
				<th>Shift No</th>
				<th>Starting Cash</th>
				<th>Total Cash</th>
				<th>Total Credit</th>
				<th>Total Check</th>
				<th>Total</th>
				<th>Close</th>
			</tr>
		
		
		");
		
		
		$sql = "SELECT * FROM register_shift r1, register r2 WHERE r2.is_open = '1' and r1.register_no = r2.register_no and r2.store_no = '{$storeNo}' and r1.shift_open = '1'";
		$result = $conn->query($sql);	//select items from the store that are expired
		
		
		
		
		while ($row = $result->fetch_assoc()) {	
			$cashval = 0;
			$cardval =0;
			$checkval =0;
			$total = 0;
		
		
			$sql_one = "SELECT sum(`payment_ammount`) FROM `payment` WHERE shift_no = '{$row['shift_no']}' and payment_type = 'cash'";
			$res_one = $conn->query($sql_one);
	
			while($row_one = $res_one->fetch_assoc()){
				$cashval = $row_one['sum(`payment_ammount`)'];
			}
			
			$sql_two = "SELECT sum(`payment_ammount`) FROM `payment` WHERE shift_no = '{$row['shift_no']}' and payment_type = 'credit'";
			$res_two = $conn->query($sql_two);
			while($row_two = $res_two->fetch_assoc()){
				$cardval = $row_two['sum(`payment_ammount`)'];
			}
			
			$sql_three = "SELECT sum(`payment_ammount`) FROM `payment` WHERE shift_no = '{$row['shift_no']}' and payment_type = 'check'";
			$res_three = $conn->query($sql_three);
			while($row_three = $res_three->fetch_assoc()){
				$checkval = $row_three['sum(`payment_ammount`)'];
			}
			
			$total = $cashval + $checkval + $cardval;
			
			
		
			echo("
			<form action='' method='post'>
				<input type = 'hidden' name='register_no' value ='{$row['register_no']}'> 
				<input type = 'hidden' name='shift_no' value ='{$row['shift_no']}'> 
				<tr>
					<td>{$row['register_no']}</td>
					<td>{$row['shift_no']}</td>
					<td>$ {$row['starting_cash']}</td>
					<td>$ {$cashval}</td>
					<td>$ {$cardval}</td>
					<td>$ {$checkval}</td>
					<td>$ {$total}</td>
					<td> <input type = 'submit' name='Submit' Value = 'Close'> </td>
				</tr>
			</form>
			");


		
	
		}
		echo("</table></html>"); //html closer
		$conn->close();	//sql closer

?>
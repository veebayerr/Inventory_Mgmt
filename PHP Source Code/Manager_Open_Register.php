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
		$sql = "UPDATE `register` SET `is_open` = '1' WHERE `register`.`register_no` = {$_POST['register_no']}";
		$conn->query($sql);
		$date = date("Y-m-d");
		$sql = "INSERT INTO `register_shift` (`shift_no`, `open_date`, `close_date`, `register_no`, `starting_cash`, `shift_open`) VALUES (NULL, '{$date}', '0000-00-00', '{$_POST['register_no']}', '{$_POST['starting_cash']}', '1')";
		$conn->query($sql);
		
		echo("Shift number ". $conn->insert_id . " opened.<br>");
		
		
		
	}
	
	
		
		$sql = "SELECT * FROM `register` WHERE store_no = '{$storeNo}' and is_open = '0'";
		$result = $conn->query($sql);	//select items from the store that are expired
		
		//table construct here
		echo("
		<h1>Open Register</h1>
			
			<table border ='1'>
			<tr>
				<th>Register No</th>
				<th>Starting Cash</th>
				<th>Open/Close</th>
			</tr>
		
		
		");
		
		while ($row = $result->fetch_assoc()) {	
	
		
		echo("
			<form action='' method='post'>
				<input type = 'hidden' name = 'register_no' value = '{$row['register_no']}'>
				<tr>
					<td>{$row['register_no']}</td>
					<td>
						<input type = number min ='0.00' max = '10000.00' name = 'starting_cash' value = '{$startCash}'>
					</td>
					<td>
						<input type = 'submit' name ='Submit' Value ='Open'>
					</td>
				</tr>
				
			</form>
			");
	
		}
		echo("</table></html>"); //html closer
		$conn->close();	//sql closer

?>
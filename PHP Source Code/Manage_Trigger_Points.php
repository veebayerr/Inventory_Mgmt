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
	

	echo("<html><h1>Manage Trigger Points</h1>");	//html consturct
	
	if(isset($_POST['update_table'])){
		$i=$_POST['size']-1;
		while($i>=0){
			$tno = $_POST['trigger_no_'.$i];
			$tva = $_POST['trigger_point_'.$i];
			$sql = "UPDATE `trigger_point` SET `trigger_point` = '{$tva}' WHERE `trigger_point`.`no` = {$tno}";
			$conn->query($sql);
			$i--;
		}
		
		
	}
	
	
	if(isset($_POST['new_trigger'])){
		$sql_one = "SELECT * FROM `items` WHERE `barcode` = '{$_POST['barcode']}'";	//get the correct item number
		$res_one = $conn->query($sql_one);
		$item_no = 0;
		while ($row = $res_one->fetch_assoc()) {	
			$item_no = $row['item_no'];
		}
		
		$sql = "INSERT INTO `trigger_point` (`no`, `item_no`, `store_no`, `trigger_point`) VALUES (NULL, '{$item_no}', '{$storeNo}', '{$_POST['qty']}')";
		$conn->query($sql);
	}
	
	
	
	
	$sql = "SELECT * FROM trigger_point t1, items i1 WHERE t1.store_no ='{$storeNo}' and i1.item_no = t1.item_no";
	$result = $conn->query($sql);	//complicated sql statement 
	//gets the sum of items in the managers store by item name, and figures out if the manager is below the trigger point
	
	//builds the table
	echo("
			<table border ='1'>
			<tr>
				<th>Barcode</th>
				<th>Item name</th>
				<th>Item Producer</th>
				<th>Trigger Point</th>
			</tr>
	");
	$i=0;
	echo("
		<form action ='' method ='post'> 
		
			
			<input type = 'submit' name = 'update_table' value = 'Save Table'>
	
	");
	while ($row = $result->fetch_assoc()) {	
			echo("
				<input type ='hidden' name = 'trigger_no_{$i}' value = ' {$row['no']} '>
				<tr>
					<td>{$row['barcode']}</td>
					<td>{$row['item_name']}</td>
					<td>{$row['item_producer']}</td>
					<td>
						<input min = '0' max = '9999' maxlength = '4'  type='number' name='trigger_point_{$i}'   value = '{$row['trigger_point']}'>
					</td>
				</tr>
			");
	$i++;

	}
	
	
	echo("
			<input type='hidden' name ='size' value = '{$i}'>
		</form>
	");
	
	
	


	
	
	
	echo("</table>");//html closer
	
	
		echo("
	<br>
	Create New Trigger Point<br>
	<form action ='' method ='post'> 
		<input type = 'number' name = 'barcode' min='0' value='0'>: Barcode<br>
		<input type = 'number' name = 'qty' min='0' value='0'>: Trigger Point<br>
		<input type = 'submit' name = 'new_trigger' value='Submit'>
	</form>
	</html>
	");
	$conn->close();	//sql closer
?>
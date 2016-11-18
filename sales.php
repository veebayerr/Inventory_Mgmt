<?php
	$servername = 'localhost';	//dont chanage
	$username = 'php';	//username for the application
	$password = 'dnFRr196rsSK7s0i';			//login credentials php has access to insert and select
	$dbname = 'joomla';		//should never need to be changed
	
	$userId = NULL;	//passed from joomla to this application
	$storeNo = NULL;	// passed from joomla to this application
	$conn = new mysqli($servername,$username,$password,$dbname);		//sql functions mysqli
	$shift_no = 0;	//keeps the current shift number
	$print_tList = 1;	//control variable
	$transaction_number = 0; //the current running transaction number
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);	//establish connection with sql database
	}

	if(isset($_GET['storeNo'])){
		$storeNo = $_GET['storeNo'];	//gets the store no passed from joomla
	}//end storeno
	if(isset($_GET['userId'])){
		$userId = $_GET['userId'];		//gets the employee id passed from joomla
	}//end userid
	if(isset($_POST['storeNo'])){
		$storeNo = $_POST['storeNo'];	//gets the store no passed from joomla
	}//end storeno
		if(isset($_POST['userId'])){
		$userId = $_POST['userId'];		//gets the employee id passed from joomla
	}//end userid

	echo("<html><h1>Cash Register</h1>");	//html consturct
	
	//just grabs the shift_number
	if(isset($_POST['submit'])){
		$shift_no = $_POST['shift_no'];	//grab the shift number all forms contain this, as to not forget it
	}

	/*
	Creates a new transaction by inserting the current date store number shift etc
	returns the transaction number to be used by roll_transaction, and roll_refund_transaction
	*/
	if(isset($_POST['new_transaction'])){
		$print_tList = 0 ;
		$shift_no = $_POST['shift_no'];
		$dt = date("Y-m-d");	//get the date 2016-11-17
		$sql = "INSERT INTO `transaction` (`transaction_no`, `transaction_date`, `price_before_tax`, `employee_no`, `store_no`, `price_after_tax`, `register_no`, `shift_no`) VALUES (NULL, '{$dt}', '', '{$userId}', '{$storeNo}', '0.00', '', '{$shift_no}');";
		$conn->query($sql);
		$transaction_number = $conn->insert_id;	//gets the transaction number, last sql insert id
		echo(genScanner($transaction_number,$shift_no,$storeNo,$userId));	//print the barcode scanner
	}//end new transaction
	
	/*
		Start the refund process, creates a new transaction to be used for the refund
		returns the transaction number
	*/
	if(isset($_POST['create_refund'])){
		echo("A refund will require a managers approval.<br>");
		$print_tList = 0 ;
		$shift_no = $_POST['shift_no'];
		$dt = date("Y-m-d");	//2016-11-17
		$sql = "INSERT INTO `transaction` (`transaction_no`, `transaction_date`, `price_before_tax`, `employee_no`, `store_no`, `price_after_tax`, `register_no`, `shift_no`) VALUES (NULL, '{$dt}', '', '{$userId}', '{$storeNo}', '0.00', '', '{$shift_no}');";
		$conn->query($sql);
		$transaction_number = $conn->insert_id;	//last sql insert
		echo(genRefundScanner($transaction_number,$shift_no,$storeNo,$userId));//print the barcode scanner for refund mode
	}//end create refund
	
	
	/*
		The final stage of the refund, (after the manager approves the refund)
		lets the cashier know how much change to given
		updates inventory and payment info
	*/
	if(isset($_POST['Remove_Refund'])){
		echo("<h2>Change to be given " . $_POST['refund_total'] ."</h2><br>");
		$sql = "UPDATE `item_sold` SET `needs_refund` = '0' WHERE `item_sold`.`sale_no` = {$_POST['sale_no']};";	//turn the refund flag off
		$shift_no = $_POST['shift_no'];
		$conn->query($sql);
		$dt = date("Y-m-d");	//get current date
		$sql = "INSERT INTO `payment` (`payment_no`, `payment_type`, `payment_ammount`, `transaction_no`, `credit_card_type`, `credit_card_no`, `credit_card_exp`, `credit_card_security_no`, `check_no`, `cash_back`, `payment_date`, `store_no`, `shift_no`) VALUES (NULL, 'Cash', '{$_POST['refund_total']}', '1', 'NULL', NULL, NULL, NULL, NULL, '0.00', '{$dt}', '{$storeNo}', '{$shift_no}');";
		$conn->query($sql);	//place the payment into the list (a negative number means money given out)
	}//end remove_refund
	

	/*
		Creates the table of refunds that have been approved by a manager
		allows the cashier to find refunds approved in store and cash out the refund
		for the customer
	*/
	if(isset($_POST['finalize_refund'])){
		$print_tList = 0 ;	//php control variable
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		echo("Refunds approved by a manager<br>");
		$qty_returned = 0;
		
		$sql = "SELECT * FROM `item_sold` WHERE `needs_refund` ='-1' and store_no ='{$storeNo}'"; //get the refunds approved by the manager of the current store
		$res = $conn->query($sql);	
		
		//builds the refund table
		echo("
		<table border='1'>
			<tr>
				<th>Item No</th>
				<th>Item Qty</th>
				<th>Refund Total</th>
				<th>Cash Out</th>
			</tr>
		");
		
		while ($row = $res->fetch_assoc()) {
			$qty_returned = $row['quantity_sold'];	//qty returned
			$item_no = $row['item_no'];	//the stock no being returned
			$refund_total = $row['sale_price'];	//the refund price
			
			//fill the table in
			echo("
			<tr>
				<td>{$item_no}</td>
				<td>{$qty_returned}</td>
				<td>{$refund_total}</td>
				<td>
				<form action='' method='post'>
					  <input type = 'hidden' name = 'sale_no' value = '{$row['sale_no']}'>
					  <input type = 'hidden' name = 'bcd' value = '{$row['item_no']}'>
					   <input type = 'hidden' name = 'refund_total' value = '{$refund_total}'>
					   <input type ='hidden' name = 'qty_returned' value = '{$qty_returned}'>
					  <input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
					  <input type = 'submit' name = 'Remove_Refund' Value = 'Cash out'>
					  <input type = 'hidden' name ='finalize_refund'>
				</form>
				</td>
			</tr>
			");
		}
		echo("</table>");
		goBacktoMenu($shift_no);
	}//end finalize refund
	

	/*
		when the cashier in roll_transaction removes an item from the transaction
		Update inventory back to normal, and remove it from the transaction list
	*/
	if(isset($_POST['Delete_item'])){
		
		$print_tList = 0 ;	//php control variable
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$qty_in_stock = getQty($_POST['bcd'], $conn);
		$qty_in_stock = $qty_in_stock + $_POST['qty_sold'];	//add back into inventory
		updateQty($_POST['bcd'], $qty_in_stock, $conn);	//update the inventory number
		Delete_item_Sold($_POST['Delete_item'], $conn);	//delete the item from the transaction list
		echo(genScanner($transaction_number,$shift_no,$storeNo, $userId)); //print the barcode module	
		printTransaction($transaction_number, $conn, $shift_no, $storeNo, $userId);	//print the current transaction
	
	}
	
	/*
		Keeps a transaction into a loop until payment is desired
		allows cashier to keep adding items to a transaction
	*/
	if(isset($_POST['roll_transaction'])){
		$print_tList = 0 ;	//php control variable
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$item_price = 0;
		$discount = 0.0;
		$exp_dt = '3000-01-01'; //date format
		$dt = date("Y-m-d");	//get current date
		echo(genScanner($transaction_number,$shift_no,$storeNo,$userId)); //print the barcode module
		$qty_in_stock = getQty($_POST['barcode'],$conn);	//get the qty in stock currently
		updateQty($_POST['barcode'], ($qty_in_stock - $_POST['qty']), $conn);	//update the qty in stock (being sold)
		insertItemSold($_POST['barcode'],$transaction_number , $_POST['qty'], $conn, $storeNo);	//insert into item sold
		isExpired($_POST['barcode'], $conn);	//check to see if the item is expired
		printTransaction($transaction_number, $conn, $shift_no, $storeNo, $userId);	//print the transaction list
		goBacktoMenu($shift_no);		//print the button to return to the main menu
	}//end roll transaction
	
		/*
			Finalizes the transaction
			allows a recipt to be printed
			allows user to go back to main menu
		*/
	if(isset($_POST['Finish_Transaction'])){
		$print_tList = 0 ;	//php control variable
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		printTransactionFinal($transaction_number, $conn, $shift_no, $storeNo, $userId);	//item summary
		echo("___________________________________________________________________________<br>");
		printPmtListFinal($transaction_number, $conn);	//pmt summary
		echo("___________________________________________________________________________<br>");
		echo("<input type='button' onclick='window.print()' value = 'Print' />");	//print recipt
		goBacktoMenu($shift_no); //return to main menu
	}//end finalize transaction
		
		
	/*
		Allows cashier to add numerous items to a refund for the manager to approved
		Sets the needs refund flag to 1 and makes cashier wait for 
		approval before the refund is finalized
	*/
	if(isset($_POST['roll_refund_transaction'])){
		$print_tList = 0 ;	//php control variable
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$item_price = 0;
		$discount = 0.0;
		$exp_dt = '3000-01-01'; //date format
		$dt = date("Y-m-d");	//get current date
		echo(genRefundScanner($transaction_number,$shift_no,$storeNo,$userId)); //print the barcode module
		$qty_in_stock = getQty($_POST['barcode'],$conn);	//get qty in stock
		updateQty($_POST['barcode'], ($qty_in_stock - $_POST['qty']), $conn);	//place refunded items back into inventory
		insertItemSoldRefund($_POST['barcode'],$transaction_number , $_POST['qty'], $conn, $storeNo);	//update inventory
		printTransactionFinal($transaction_number, $conn, $shift_no, $storeNo, $userId);	//print the refund list
		goBacktoMenu($shift_no);
	}//end roll transaction

	/*
		Prints the payment list from a specified transaction number
	*/
	if(isset($_POST['payment'])){
		$print_tList = 0;
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		printPmtList($shift_no,$transaction_number, $conn);
	}//end payment
	
	/*
		Gets the payment type and generates the correct form for the cashier to fill in
		ie credit form loads credit related inputs only
	*/
	if(isset($_POST['pmt_type'])){
		$print_tList = 0;
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$type = $_POST['pmt_type'];
		
		if($type==0){
			//cash payment form
			echo("
				<form action='' method='post'>
					  <input type = 'number' name = 'value' min = '0.00' max ='999999.99' maxlength='10' value = '0.00' step = '.01'> Amt : <br>
					  <input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
					  <input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
					  <input type = 'submit' name = 'Cash_Submit' Value = 'Go'>
				</form>
			");
		}
		if($type==1){
			//card payment form
			echo("
				<form action='' method='post'>
						<input type = 'number' name = 'value' min = '0.00' max ='999999.99' maxlength='10' value = '0.00' step = '.01'> Amt : <br>
						<input type = 'text' name = 'card_no' value = '' required> Card No : <br>
						<input type = 'date' name = 'exp_date' required> Exp Date : <br>
						<input type = 'number' required name = 'ccv' min = '0' max = '9999'> CCV : <br>
						<input type='radio' name='card_type' value='0' selected> Visa<br>
						<input type='radio' name='card_type' value='1'> Amex<br>
						<input type='radio' name='card_type' value='2' > Master Card<br>
						<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
					  <input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
					  <input type = 'submit' name = 'Credit_Submit' Value = 'Go'>
				</form>
			");
		}
		if($type==2){
			//check payment form
			echo("
				<form action='' method='post'>
					  <input type = 'number' name = 'value' min = '0.00' max ='999999.99' maxlength='10' value = '0.00' step = '.01'> Amt : <br>
					  <input type = 'text' name = 'check_no' required value ='0'> Check No : <br>
					  
					  <input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
					  <input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
					  <input type = 'submit' name = 'Check_Submit' Value = 'Go'>
				</form>
			");
		}
		
	}//end payment type
	
	//if payment type is cash (after the form has been filled out for cash submit)
	if(isset($_POST['Cash_Submit'])){
		$print_tList = 0;
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$store_no = getStoreNo($shift_no, $conn); //grabs store number
		$dt = date("Y-m-d");	//grabs date
		$sql = "INSERT INTO `payment` (`payment_no`, `payment_type`, `payment_ammount`, `transaction_no`, `credit_card_type`, `credit_card_no`, `credit_card_exp`, `credit_card_security_no`, `check_no`, `cash_back`, `payment_date`, `store_no`, `shift_no`) VALUES (NULL, 'Cash', '{$_POST['value']}', '{$transaction_number}', NULL, NULL, NULL, NULL, NULL, '0.00', '{$dt}', '{$store_no}', '{$shift_no}');";
		$conn->query($sql);
		printPmtList($shift_no,$transaction_number, $conn);	//prints the current payment list
	}//end cash
		
	//if payment type is credit (after the form has been filled out for credit submit)
	if(isset($_POST['Credit_Submit'])){
		$print_tList = 0;
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$store_no = getStoreNo($shift_no, $conn);
		$dt = date("Y-m-d");
		$sql = "INSERT INTO `payment` (`payment_no`, `payment_type`, `payment_ammount`, `transaction_no`, `credit_card_type`, `credit_card_no`, `credit_card_exp`, `credit_card_security_no`, `check_no`, `cash_back`, `payment_date`, `store_no`, `shift_no`) VALUES (NULL, 'Credit', '{$_POST['value']}', '{$transaction_number}', '{$_POST['card_type']}', '{$_POST['credit_card_no`']}', '{$_POST['exp_date']}', '{$_POST['ccv']}', NULL, '0.00', '{$dt}', '{$store_no}', '{$shift_no}');";
		$conn->query($sql); //place into payment table
		printPmtList($shift_no,$transaction_number, $conn);	
	}//end credit
	
	//if payment type is check (after the form has been filled out for check submit)
	if(isset($_POST['Check_Submit'])){
		$print_tList = 0;
		$shift_no = $_POST['shift_no'];
		$transaction_number = $_POST['transaction_number'];
		$store_no = getStoreNo($shift_no, $conn);
		$dt = date("Y-m-d");
		$sql = "INSERT INTO `payment` (`payment_no`, `payment_type`, `payment_ammount`, `transaction_no`, `credit_card_type`, `credit_card_no`, `credit_card_exp`, `credit_card_security_no`, `check_no`, `cash_back`, `payment_date`, `store_no`, `shift_no`) VALUES (NULL, 'Check', '{$_POST['value']}', '{$transaction_number}', NULL, NULL, NULL, NULL, '{$_POST['check_no']}', '0.00', '$dt', '{$store_no}', '{$shift_no}');";
		$conn->query($sql);
		printPmtList($shift_no,$transaction_number, $conn);	
	}//end check payment
		



	/*
		When the cashier has only a shift selected this is the menu that starts the different transaction routes
		will load appropriate transaction path 
	*/
	if($shift_no != 0 && $print_tList == 1){
		echo("Main Menu <br>___________________________________________________________________________<br><br>
		<form action ='' method ='post'> 
			<input type = 'submit' name = 'new_transaction' value = 'New Transaction'>
			<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
		</form>
		<form action ='' method ='post'> 
			<input type = 'submit' name = 'create_refund' value = 'Create Refund'>
			<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
		</form>
		<form action ='' method ='post'> 
			<input type = 'submit' name = 'finalize_refund' value = 'Finalize Refund'>
			<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
		</form>
		
		<br><br>___________________________________________________________________________
		");
	}//end main menu


	
	//if the cashier doesnt have a shift selected make them select one before continuing
	if($shift_no == 0 ){
		$sql = "select * from register r1, register_shift r2 where r1.store_no = '{$storeNo}' and r2.shift_open = '1' and r1.is_open = r2.shift_open and r1.register_no = r2.register_no";
		$result = $conn->query($sql);	//complicated sql statement 
		//gets the sum of items in the managers store by item name, and figures out if the manager is below the trigger point		
		echo("
			Please select an open shift to begin. <br>
			<form action ='' method ='post'> 
				<select name = 'shift_no'>
		");
			
		while ($row = $result->fetch_assoc()) {	
			echo("
				<option value = '{$row['shift_no']}' selected>{$row['shift_no']}</option>
			");
		}
		echo("
		</select>
		<input type ='submit' name = 'submit'>
		</form>
		");
	}//end select shift
	
	
	
	echo("</html>");//html closer
	$conn->close();	//sql closer
	
	/*
		generates the normal transaction barcode scanner form
	*/
	function genScanner($transaction_number, $shift_no, $storeNo,$userId){
		return("
			<form action ='' method ='post'> 
				Bcd:<input type = 'number' name = 'barcode' min ='0' max = '999999' value = '0'>  
				Qty:<input type='number' name = 'qty' min='-9999' max='9999' value = '1'>  
				<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
				<input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
				<input type = 'submit' name = 'roll_transaction'>
			</form>
				___________________________________________________________________________<br>
		");	
	}
	
	/*
		generates the refund transaction scanner
	*/
	function genRefundScanner($transaction_number, $shift_no, $storeNo,$userId){
		return("
			<form action ='' method ='post'> 
				Bcd:<input type = 'number' name = 'barcode' min ='0' max = '999999' value ='0'>  
				Qty:<input type='number' name = 'qty' min='-9999' max='9999' value ='-1'>  
				<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
				<input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
				<input type = 'submit' name = 'roll_refund_transaction'>
			</form>
				___________________________________________________________________________<br>
		");	
	}
	
	
	/*
		generates the payment button form
	*/
	function printPmtBtn($shift_no, $transaction_number){
		echo("
			<form action='' method='post'>
				<input type = 'submit' name = 'payment' value = 'Pay Now'>
				<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
				<input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>			
			</form>
		");
		
	}
	
	/*
		update the item inventory quantity in stock
	*/
	function updateQty($stock_no, $qty, $conn){
		$sql = "UPDATE `item_inventory` SET `item_quantity` = '{$qty}' WHERE `item_inventory`.`no` = '{$stock_no}';"; //working
		$conn->query($sql);
	}
	
	/*
		get qty in stock of item
	*/
	function getQty($bcd, $conn){
		$sql = "SELECT `item_quantity` from item_inventory where item_no = {$bcd}";	//working
		$res = $conn->query($sql);
		$qty_in_stock = 0;
		while($row = $res->fetch_assoc()){
			$qty_in_stock = $row['item_quantity'];
		}
				
		return($qty_in_stock);	
	}//end get qty
	
	/*
		Place item_sold into table, link it to current transaction non refund
		set the cost based on item price discount and qty sold
	*/
	function insertItemSold($barcode,$transaction_number , $qty_sold, $conn, $storeNo){
		$dt = date("Y-m-d");	//get current date
		$sql_two = "INSERT INTO `item_sold` (`sale_no`, `item_no`, `transaction_no`, `sale_price`, `quantity_sold`, `needs_refund`, `register_no`, `store_no`, `date`) VALUES (NULL, '{$barcode}', '{$transaction_number}', '0', '{$qty_sold}', '0', '1', '{$storeNo}', '{$dt}');";
		$conn->query($sql_two);
		$insertValue = $conn->insert_id;
		$sale_price = (Get_Sale_Price($barcode, $conn)) * $qty_sold;
		Update_Sale_Price($insertValue, $sale_price, $conn);
			
	}//end insert sold
	/*
		Place item_sold into table, link it to current transaction refund
		set the cost based on item price discount and qty sold
	*/
	function insertItemSoldRefund($barcode,$transaction_number , $qty_sold, $conn, $storeNo){
		$dt = date("Y-m-d");	//get current date
		$sql_two = "INSERT INTO `item_sold` (`sale_no`, `item_no`, `transaction_no`, `sale_price`, `quantity_sold`, `needs_refund`, `register_no`, `store_no`, `date`) VALUES (NULL, '{$barcode}', '{$transaction_number}', '0', '{$qty_sold}', '1', '1', '{$storeNo}', '{$dt}');";
		$conn->query($sql_two);
		$insertValue = $conn->insert_id;
		$sale_price = (Get_Sale_Price($barcode, $conn)) * $qty_sold;
		Update_Sale_Price($insertValue, $sale_price, $conn);
			
	}//end insert sold refund
	
	/*
		Update the sale price on the item sold based on input
	*/	
	function Update_Sale_Price($sale_no, $price, $conn){
		$sql = "UPDATE `item_sold` SET `sale_price` = '{$price}' WHERE `item_sold`.`sale_no` = {$sale_no};";
		$conn->query($sql);
	}//end update sale price
	
	/*
		Get the sale price of an item
		based on qty sold item price and the current discount
	*/
	function Get_Sale_Price($barcode, $conn){
		$sql =  "SELECT * FROM `item_inventory` WHERE `no` = '{$barcode}'";
		$res = $conn->query($sql);
		$item_sale_price = 0.00;
		$item_discount_percent = 0.00;
		while($row = $res->fetch_assoc()){
			$item_sale_price = $row['item_sale_price'];
			$item_discount_percent = $row['item_discount_percent'];
		}
		$return_price  = $item_sale_price * (1.0-$item_discount_percent); //calculate discount
		return($return_price);
	}//end get_sale_price
	
	/*
		Remove an item sold from a transaction
	*/
	function Delete_item_Sold($sale_no, $conn){
		$sql = "DELETE FROM `item_sold` WHERE `item_sold`.`sale_no` = {$sale_no}";
		$conn->query($sql);
	}//end delete item sold
	
	/*
		Check to see if the current item passed in is expired or not
		Print to user if the item is expired
	*/
	function isExpired($barcode, $conn){
		$exp_dt = '3000-01-01'; //date format
		$dt = date("Y-m-d");	//get current date
		$sql_one = "SELECT * FROM `item_inventory` WHERE no = '{$barcode}'";
		$res_one = $conn->query($sql_one);
		while ($row = $res_one->fetch_assoc()) {	
			$exp_dt = $row['expiration_date'];
		}
		if($exp_dt <= $dt){
			echo("<h2><font color='red'>This item is Expired</font></h2>");	//check to see if the item is expired
		}
	}//end is expired
	
	/*
		Prints a table of all the items currently being sold in a transaction
		Given some info on the current transaction
	*/
	function printTransaction($transaction_number, $conn, $shift_no, $storeNo, $userId){
		$sql_three = "select * from item_sold i1, item_inventory i2, items i3 where i1.item_no = i2.no and i2.item_no = i3.item_no and i1.transaction_no = '{$transaction_number}' group by sale_no desc";
		$res_three = $conn->query($sql_three); //get the items sold in the transaction
		$transaction_total = 0.00;
		$exp_dt = '3000-01-01'; //date format
		$dt = date("Y-m-d");	//get current date
		
		//build the table
		echo("
				<table border ='.5'>
						<tr>
							<thread>
							<th>Item Name</th>
							<th>QTY</th>
							<th>Cost</th>
							<th>Discount</th>
							<th>Total Cost</th>
							<th>Remove Item</th>
							</thread>
						</tr>
		"); //end table headers
		
		while ($row = $res_three->fetch_assoc()) {	
			$exp_string = ""; //will place (expired) next to an expired item
			$current_item_total = $row['sale_price'];	//gets the item total of a single item
			$transaction_total = $transaction_total + $current_item_total;	//updates transaction total
			$fdp = $row['item_discount_percent'] * 100.00;	//turns discount back into a percent
			$exp_dt = $row['expiration_date']; //grabs the expiration date of the item
			
			if($exp_dt <= $dt){
				$exp_string = "(expired)";	//check to see if the item is expired
			}
			//fill in the rest of the table
			echo("
			<tr>
				<td>{$row['item_name']} {$exp_string}</td>
				<td>{$row['quantity_sold']}</td>
				<td>$ {$row['item_sale_price']}</td>
				<td>{$fdp}%</td>
				<td>$ {$current_item_total}</td>
				
				
				<td>
					<form action='' method='post'>
						<input type = 'submit' name = 'Remove' Value = 'Remove'>
						<input type = 'hidden' name = 'bcd' Value = '{$row['item_no']}'>
						<input type = 'hidden' name = 'shift_no' value = '{$shift_no}'>
						<input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
						<input type = 'hidden' name = 'Delete_item' value = '{$row['sale_no']}'>
						<input type = 'hidden' name = 'qty_sold' value = '{$row['quantity_sold']}'>
					</form>
				</td>
				
			</tr>
			");	
		}//end while
	
		$sql_update = "UPDATE `transaction` SET `price_before_tax` = '{$transaction_total}' WHERE `transaction`.`transaction_no` = {$transaction_number};";
		$conn->query($sql_update); //set the transaction total
		$transaction_total = number_format($transaction_total, 2, '.', '');
		echo("<h2>Total is : $ ".$transaction_total . "</h2><br>");	//indicate transaction total
		printPmtBtn($shift_no, $transaction_number); //print the payment button
		echo("</table><h2>Total is : $ ". $transaction_total . "</h2><br>");
		printPmtBtn($shift_no, $transaction_number);
		
		
	}//end print Transaction
	
	/*
		Same funcion as Print transaction
		however this method is used as a recipt
		does not have payment button
		or remove item button
	*/
	function printTransactionFinal($transaction_number, $conn, $shift_no, $storeNo, $userId){
		$sql_three = "select * from item_sold i1, item_inventory i2, items i3 where i1.item_no = i2.no and i2.item_no = i3.item_no and i1.transaction_no = '{$transaction_number}' group by sale_no desc";
		$res_three = $conn->query($sql_three); //grabs metrics related to current transaction
		$transaction_total = 0.00;
		$exp_dt = '3000-01-01'; //date format
		$dt = date("Y-m-d");	//get current date
		//build table
		echo("
				<table border ='.5'>
						<tr>
							<thread>
							<th>Item Name</th>
							<th>QTY</th>
							<th>Cost</th>
							<th>Discount</th>
							<th>Total Cost</th>
							</thread>
						</tr>
		"); //end table headers
		
		while ($row = $res_three->fetch_assoc()) {	
			$exp_string = "";
			$current_item_total = $row['sale_price'];
			$transaction_total = $transaction_total + $current_item_total;
			$fdp = $row['item_discount_percent'] * 100.00;
			$exp_dt = $row['expiration_date'];
			
			if($exp_dt <= $dt){
				$exp_string = "(expired)";	//check to see if the item is expired
			}
			
			echo("
			<tr>
				<td>{$row['item_name']} {$exp_string}</td>
				<td>{$row['quantity_sold']}</td>
				<td>$ {$row['item_sale_price']}</td>
				<td>{$fdp}%</td>
				<td>$ {$current_item_total}</td>
			</tr>
			");	
		}//end while
		$sql = "UPDATE `transaction` SET `price_before_tax` = '{$transaction_total}' WHERE `transaction`.`transaction_no` = {$transaction_number};";
		$conn->query($sql); //update the transaction for the last time
		echo("</table>");
	}//end print Transaction
	
	/*
		Prints a button to return to the main menu
	*/
	function goBacktoMenu($shiftNo){
		echo("
					<form action='' method='post'>
						<input type = 'submit' name = 'submit' Value = 'Main Menu'>
						<input type = 'hidden' name = 'shift_no' value = '{$shiftNo}'>
					</form>
		");
	}//end goBacktoMenu
	
	/*
		Gets the total payment on a specific transaction
		returns the total
	*/
	function getPmtOnTrans($transaction_number, $conn){
		$sql = "SELECT sum(`payment_ammount`) FROM `payment` WHERE `transaction_no` = '{$transaction_number}'";
		$res = $conn->query($sql);
		$total = 0.00;
		while ($row = $res->fetch_assoc()) {
			$total = $row['sum(`payment_ammount`)']; // add to sum
		}
		return($total);
	}//end getPmtOnTransq
	
	/*
		Gets the transaction total from transaction
		returns the total 
	*/
	function getTransTotal($transaction_number, $conn){
		$sql = "SELECT `price_before_tax` FROM `transaction` WHERE `transaction_no` = '{$transaction_number}'";
		$res = $conn->query($sql);
		$total = 0.00;
		while ($row = $res->fetch_assoc()) {
			$total = $row['price_before_tax'];
		}
		return($total);	
	}//end get trans total
	
	/*
		Function also used in recipt for customer
		prints a list of all payments applied to the current transaction
	*/
	function printPmtListFinal($transaction_number, $conn){
		$sql = "SELECT * FROM `payment` WHERE `transaction_no` = '{$transaction_number}'";
		$res = $conn->query($sql);
		$total_paid = 0.00;
		$trans_total = getTransTotal($transaction_number,$conn);
		echo("Transaction Total : $ " . $trans_total . "<br>");
		while ($row = $res->fetch_assoc()) {
			$total_paid = $total_paid + $row['payment_ammount'];
			echo("Payment : " . $row['payment_no'] . " Type " . $row['payment_type'] . " Pmt Amt : $ " . $row['payment_ammount'] . "<br>");
		}
		
		$change = $total_paid - $trans_total;
		$change = number_format($change, 2, '.', '');
		echo("Change given : $ " . $change ."<br>"); //calculates change to be given
	}//end printPmtList
	
	function printPmtList($shiftNo,$transaction_number, $conn){
		$pmt_applied = 0;
		$trans_total = 0;
		$remaining = 0;
		$pmt_applied = getPmtOnTrans($transaction_number, $conn); //get applied payments
		$trans_total = getTransTotal($transaction_number, $conn);	//get transaction total
		$remaining = ($trans_total - $pmt_applied);	//calulate remaining balance/change
		
		$pmt_applied = number_format($pmt_applied, 2, '.', '');
		$trans_total = number_format($trans_total, 2, '.', '');
		$remaining = number_format($remaining, 2, '.', '');
		
		echo("Total Paid $ " . $pmt_applied . "<br>");
		echo("Transaction Total $ " . $trans_total . "<br>");
		echo("Remaining $ " . $remaining . "<br>");
		
		if($remaining <= 0){
			//if the user has paid atleast the cost of the transaction let the cashier close out the transaction
			//and give change if applicable
					echo("___________________________________________________________________________");
					echo("<h2>Change to be given " . $remaining . "</h2>");
					echo("
					<form action='' method='post'>
						<input type = 'submit' name = 'Finish_Transaction' Value = 'Finish/Print Recipt'>
						<input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
						<input type = 'hidden' name = 'shift_no' value = '{$shiftNo}'>
					</form>
					
		");
		}//end if
		
		//allow the cashier to add another payment to the transaction
		echo("
		___________________________________________________________________________
		<h3>Payment Type</h3>
		<form action='' method='post'>
			  <input type='radio' name='pmt_type' value='0' selected> Cash<br>
			  <input type='radio' name='pmt_type' value='1'> Card<br>
			  <input type='radio' name='pmt_type' value='2'> Check<br>
			  <input type = 'hidden' name = 'shift_no' value = '{$shiftNo}'>
			  <input type = 'hidden' name = 'transaction_number' value = '{$transaction_number}'>
			  <input type = 'submit' name = 'submit' Value = 'Go'>
		</form>
		
		");
	}//end pmtList
	
	/*
		Resolves the store number given the shift number
		returns the store number
	*/
	function getStoreNo($shiftNo, $conn){
		$sql_one = "SELECT `register_no` FROM `register_shift` WHERE `shift_no` ='{$shiftNo}'"; //gets the registers info from the shift (register_no) specifically
		$res_one = $conn->query($sql_one);
		$register_no = 0;
		$store_no = 0;
		while ($row = $res_one->fetch_assoc()) {
			$register_no = $row['register_no'];	
		}
		$sql_two = "SELECT `store_no` FROM `register` WHERE `register_no` ='{$register_no}'";	//selects register info from register_no (contains store_no)
		$res_two = $conn->query($sql_two);
		while ($row = $res_two->fetch_assoc()) {
			$store_no = $row['store_no'];	//fetches store no
		}
		return($store_no);	//returns the store number
	}//end get store_no
	//end php
?>
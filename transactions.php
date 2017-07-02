<!--Page created by Salma osman-->
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require 'resources/transact_res.php'; ?>    
    <title>Transactions</title>
    
</head>

<body>
    
    <?php
    
    session_start();
    $servername="localhost";
    $username="root";
    $password="";  
    $dbName = "pet_store_546";
    //connect to tablespace
    $conn = mysqli_connect($servername, $username, $password, $dbName);

    $payment  = $amount = $petId =  $custId = $custInfo = $petInfo = $transactionErr = "";
    $paymentErr = $amountErr = $petIdErr = $custIdErr = "";
    $petAvailable = true;
    $petExist= false;
    $custExist = false;
    

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //vlidate payment method
        if (empty( $_POST['payment'] ) ){
            $paymentErr="Payment type is Required <br>";
        } else {
            $payment = testString($_POST['payment']);
        } 
        
        
        //validate payment amount
        if (empty( $_POST['amount'] ) ){
            $amountErr ="Amount is Required <br>";
        } else {
            $amount =  testString($_POST['amount']);
            if (preg_match("^(?!0,?\d)([0-9]{1,5}[0-9]{0,}(\.[0-9]{2}))$^", $amount)){
//                echo "VALID amount";
            } else {
                $amountErr =  "INVALID amount! (correct format: 000.00)";
            }
        }
        
        
        function getPetInfo($petId){
             $petInfo = "";
             $available = 0;
            
            $query = "select name, price , available from pet where pet_id = '".$petId."'";

            global $conn;
            $result = $conn->query($query);

            //checks to see if there are any rows returned 
            if ( $result-> num_rows > 0) {
                // store data of each row in variables
                while($row = $result->fetch_assoc()) {
                    $name = $row["name"];
                    $price = $row["price"];
                    $available = $row["available"];
                    $petInfo = "Pet Name: ".$name.". Price: $".$price;
                }
                
                if ($available == 1){
                    return $petInfo;
                    $petAvailable = false;
                    $petExist = true;
                } else{
                    return $petInfo = "Pet exists but is not Available!";
                }
                
             } else {
                return $petInfo = "Pet does not exist";
            }
            
        }
        
        function getCustInfo($custId){
             $custInfo = "";
                         
             $query = "select firstname, lastname from customer where cust_id = '".$custId."'";

            global $conn;
            $result = $conn->query($query);

            //checks to see if there are any rows returned 
            if ( $result-> num_rows > 0) {
                // store data of each row in variables
                while($row = $result->fetch_assoc()) {
                    $fname = $row["firstname"];
                    $lname = $row["lastname"];
                    $custInfo = "Customer Name: ".$fname." ".$lname;
                    $custExist = true;
                }
               return $custInfo;
                
             } else {
                return $custInfo = "Customer does not exist";
            }
            
        }
        
        function  makeUnavailable($petId){
            $petStatus = "";
            $query = "update pet set available = '0' where pet_id = '".$petId."'";
            global $conn;
            if(mysqli_query($conn,$query))  {
                    $petStatus=  "pet now unavailable";

                } else   {
                   $petStatus = "Error switching pet";
                }
        
            
        
        }
        
        function makeTransaction($custId, $petId, $payment, $amount){
              
            $query = "insert into transactions (cust_id, pet_id, payment_method, payment_amount) values ('.$custId.', '.$petId.', '.$payment.', '.$amount.');";
            global $conn;
            
            if( mysqli_query($conn,$query) )  {
                
                return true;

            } else   {
                return false;
            }

            
        }
        
        //-------------------Verify and transaction btns-------------------
        if ( isset(  $_POST["btnPet"]  )  ){
            
//            //validate pet choice
//            if (empty( $_POST['petId'] ) ){
//                $petIdErr="Please enter a pet ID";
//
//            } else {
//                $petId = testString($_POST['petId']);
//                //returns pet data
//                $petInfo = getPetInfo($petId);
//            }

        } else if ( isset(  $_POST["btnCustomer"]  ) ) {
            
            
            
//             //validate Customer choice
//            if (empty( $_POST['custId'] ) ){
//                $custIdErr="Please Enter a customer ID";
//
//            } else {
//                $custId = testString($_POST['custId']);
//                $custInfo = getCustInfo($custId);
//            }

        
        } else if ( isset(  $_POST["btnTransaction"]  ) ){
            
             //validate pet choice
            if (empty( $_POST['petId'] ) ){
                $petIdErr="Please enter a pet ID";

            } else {
                $petId = testString($_POST['petId']);
                //returns pet data
                $petInfo = getPetInfo($petId);
                $petExist = true;
            }
            
            //-----------------------------------------------------
            //validate Customer choice
            if (empty( $_POST['custId'] ) ){
                $custIdErr="Please Enter a customer ID";

            } else {
                $custId = testString($_POST['custId']);
                $custInfo = getCustInfo($custId);
                $custExist = true;
            }
            
            
            
            //-------------------------------------------------
            if ($petAvailable == false){
                echo makeUnavailable($petId);
            }
            
            //ERROR here: this is evaluating to False due to structure of page
            if ($petExist && $custExist){
                if (makeTransaction($custId, $petId, $payment, $amount)){
                    $transactionErr =  "Transaction went through";
                } else {
                    $transactionErr =  "Transaction did not go through";
                }
            } else {
                echo "The customer or Pet entered does not exist";
            }
            
            

        } 
        
        //------------------------------------------
        
    } //end of $_SERVER["REQUEST_METHOD"] == "POST"
   
    //cleans up data recieved from html form/database 
    function testString($data){
            $data=trim($data);
            $data=stripslashes($data);
            $data=htmlspecialchars($data);
            return $data;
    }
    
    function getPetData(){
        $selectQuery = "select pet_id, name, price from pet where available = 1;";
        global $conn;
        
        if ($conn){
            $result = $conn->query($selectQuery);
            $counter = 0;
            if ( $result->num_rows > 0) {
                // store data of each row in array
                while($row = $result->fetch_assoc()) {
                    $id = $row["pet_id"];
                    $name = $row["name"];
                    $price = $row["price"];
                  $petData[$counter] = $id.", ".$name.", $".$price;
                    
                }
            } else{
                //no rows!
            }
        } else {
            //reocrd error in log file
        }
        
    }
    
    ?>
    
    <div class="header">
       <?php  require 'Menu/header.php'?>
    </div>
    
        
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            
            <!--     Add Pet       -->
            <label>Enter Pet ID</label>
            <br>
            <input type="text" name="petId" value="<?php echo $petId; ?>">
            <span><?php echo "<p>".$petInfo."</p>";?></span><br>
            <span><?php echo "<p style='color:firebrick;'>".$petIdErr."</p>";?></span>
            <br>
            
            <!--    verify pet Button      -->
<!--
            <br>
            <input type="submit" name="btnPet" value="Verify Pet">
            <br><br>
-->
            
            <!--     Add Customer       -->
            <label>Enter Customer ID</label>
            <br>
            <input type="text" name="custId" value="<?php echo $custId; ?>">
            <span><?php echo "<p>".$custInfo."</p>";?></span>
            <span><?php echo "<p style='color:firebrick;'>".$custIdErr."</p>";?></span>
            <br>
            
            <!--    verify customer Button      -->
<!--
            <br>
            <input type="submit" name="btnCustomer" value="Verify Customer">
            <br><br>
-->
            
            <label>Enter amount {format: 000.00}</label>
            <br> 
            <input type="text" name="amount" maxlength="6" value="<?php echo $amount; ?>">
            <span><?php echo "<p style='color:firebrick;'>".$amountErr."</p>";?></span>
            <br>
            
            
            <label>Choose Payment Option *</label><br>
            <select class="payment-option" name="payment" value="<?php echo $payment; ?>">
                    <option value="">Select...</option>
                    <option value="mastercard" <?php if (isset($payment) && $payment == 'mastercard') { echo "selected" ; } ?>  >Master Card</option>
                    <option value="visa" <?php if (isset($payment) && $payment == 'visa') { echo "selected" ; } ?> >Visa</option>
                    <option value="debitcredit" <?php if (isset($payment) && $payment == 'debitcredit') { echo "selected" ; } ?> >Debit Credit</option>
                    <option value="debit" <?php if (isset($payment) && $payment == 'debit') { echo "selected" ; } ?> >Debit</option>
                    <option value="amax" <?php if (isset($payment) && $payment == 'amax') { echo "selected" ; } ?> >AMAX</option>
            </select>
            <span><?php echo "<p style='color:firebrick;'>".$paymentErr."</p>";?></span>
            
            
            <!--    Trasnaction  Button      -->
            <br>
            
            <span><?php echo "<p style='color:firebrick;'>".$transactionErr."</p>";?></span>
            <input type="submit" name ="btnTransaction" value="Submit">
        
        </form>
        
        
        
        

    </div>
  
    
    
    <script src=""></script>
</body>
</html>

<?php
session_start();
$fNameErr = $lNameErr = "";
 $msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "pet_store_546";
    
    $conn = mysqli_connect($servername, $username, $password, $dbName);

    if (!$conn) {
        $connection_test = die("Connection Failed: ".mysqli_connect_error()."<br/>");
    } else {
        $connection_test = "Connection is succesful <br/>";
    }

    if (empty($_POST["fName"])) {
        $fNameErr = "First Name Required";
    } else {
        $firstName = $_POST["fName"];
    }

    if (empty($_POST["lName"])) {
        $lNameErr = "Last Name Required";
    } else {
        $lastName = $_POST["lName"];
    }

    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $city = $_POST["city"];

    if ($fNameErr == "" & $lNameErr == "") {
        $query = "INSERT INTO customer (firstname, lastname, phone, email, city) VALUES ('$firstName', '$lastName', '$phone', '$email', '$city')";

        if (mysqli_query($conn, $query)) {
            //echo "Customer Registered";
            $msg = "Customer Registered";
            
        } else {
//            echo die("Customer not registered: ".mysqli_connect_error());
            $msg = "Customer not registered";
        }   
    }
}

?>

<html>
    <head>
        <title>Customer Registration</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require 'resources/customer_res.php';?>
        
        <style>
            .error {color: red;}
        </style>
    </head>
    <body>
        <div class="header">
           <?php  require 'Menu/header.php'?>
        </div>
        
        <div class="container">
            <div >
                <h1>Add a customer</h1>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="container">
                    <table>
                        <tr>
                            <td>
                               <label for="fName">First Name:</label> 
                            </td>
                            <td>
                                <input type="text" class="form-control" aria-discribedby="fNameHelp" id="fName" name="fName"/>
                                <small id="fNameHelp" class="form-text text-muted"><span class="error"><?php echo $fNameErr; ?></span></small>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <label for="lName">Last Name:</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" aria-discribedby="lNameHelp" id="lName" name="lName"/>
                                <small id="lNameHelp" class="form-text text-muted"><span class="error"><?php echo $lNameErr; ?></span></small>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                               <label for="phone">Phone Number:</label> 
                            </td>
                            <td>
                                <input type="text" class="form-control" id="phone" name="phone"/>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <label for="email">Email Address:</label>
                            </td>
                            <td>
                                <input type="email" class="form-control" id="email" name="email"/>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <label for="city">City:</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="city" name="city"/>
                            </td>
                        </tr>
                        
                    </table>
                                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <span><?php echo "<p style='color:firebrick;'>".$msg."</p>";?></span>
                    <a href="DeleteCustomer.php" id="remove_customer">Remove Existing Customer</a>
                </div>
            </form>
        </div>
    </body>
</html>
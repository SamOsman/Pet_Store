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

    if ($fNameErr == "" & $lNameErr == "") {
        $query = "DELETE FROM customer WHERE firstname = '$firstName' AND lastname = '$lastName'";

        if (mysqli_query($conn, $query)) {
           // echo "Customer Deleted";
            $msg = "Customer Deleted";
        } else {
            //echo die("Customer does not exist: ".mysqli_connect_error());
            $msg = "Customer does not exist";
        }   
    }
}

?>

<html>
    <head>
        <title>Delete Customer</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require 'resources/customer_res.php'; ?>
        
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
                <h1>Delete Customer</h1>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

                    <div class="form-group">
                        <label for="fName">First Name:</label>
                        <input type="text" class="form-control" aria-discribedby="fNameHelp" id="fName" name="fName"/>
                        <small id="fNameHelp" class="form-text text-muted"><span class="error"><?php echo $fNameErr; ?></span></small>
                    </div>
                    <div class="form-group">
                        <label for="lName">Last Name:</label>
                        <input type="text" class="form-control" aria-discribedby="lNameHelp" id="lName" name="lName"/>
                        <small id="lNameHelp" class="form-text text-muted"><span class="error"><?php echo $lNameErr; ?></span></small>
                    </div>
                    <span><?php echo "<p style='color:firebrick;'>".$msg."</p>";?></span><br>
                    <button type="submit" class="btn btn-primary">Submit</button><br>
                    

            </form>
        </div>
    </body>
</html>
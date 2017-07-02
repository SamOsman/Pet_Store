<!--file created by: Salma Osman-->
<?php

//open log file and read first line
$log = fopen("log/log.txt", "r") or die("unable to open log file");
//first line of log
$table_exists = fgets($log);
echo $table_exists;
fclose($log);

if ($table_exists === "false" || $table_exists == "") {
    //create database
    include 'createDataBase.php';
} 




?>

<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/log_in_style.css">
        <title>Pet Store</title>
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
         
        $userName = $password = "";
        $userNameErr = $passwordErr =  $logInErr = "";
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //user name cannot be empty or over 15 charac. long
            if (empty($_POST['userName'])){
                $userNameErr = "Username is required";
            } else {
                $userName = testString($_POST['userName']);
                if ( strlen( $userName ) > 15 ){
                    $userNameErr = "Username cannot contain more than 15 characters";
                }
            }
            
            //password cannot be empty or over 10 charac. long
            if (empty($_POST['password'])){
                $passwordErr = "Password is required";
            } else {
                $password = testString($_POST['password']);
                if ( strlen( $userName ) > 10 ){
                    $password = "Password cannot contain more than 10 characters";
                }
            }
            
            
            
            if ( $userNameErr === "" && $passwordErr === "") {
                 //contact database and see if user info exists  
                 //for security purposes, user is NOT told whether username or password is incorrect
                if ($conn){
                    //if username exists in the database, returns true
                    if ( checkUserName($userName) ) {
                        //returns stored password hash associated with the given username 
                        if (getLogInCred($userName, $password)) {
                            //if true: password is correct
                            //set session variables:
                            $_SESSION["user"] = $userName;
                            
                            //send user to home page
                            header("Location: Index.php");
                            exit();
                        } else{
                            //passwor is incorrect
                            $logInErr  = "Username or Password incorrect!";
                        }
                    } else {
                        echo "Username or Password incorrect!";
                    }
                    
                } else {
                    fwrite($log, "LogIn Error: Could not establish connection with database Petstore");
                }
                
                           
            }
            
        }
        
        //cleans up data recieved from html form/database 
        function testString($data){
                $data=trim($data);
                $data=stripslashes($data);
                $data=htmlspecialchars($data);
                return $data;
        }
        
        function checkUserName($newUserName){
                //retirve all user names from database
                $selectQuery = "select username from log_in_cred;";
                global $conn;
                $result = $conn->query($selectQuery);

                //checks to see if there are any rows returned 
                $counter = 0;
                if ( $result->num_rows > 0) {
                    // store data of each row in array
                    while($row = $result->fetch_assoc()) {
                         $storedUserNames[$counter] = $row["username"];
                         $counter++;
                    }
                } 

                foreach ($storedUserNames as $name) {
                    //strpos() compares two strings; case-sensitive 
                    //the 2 strings passed to strpos have to be identical in order for them to return true!
                    if (strpos($newUserName, $name) !== FALSE) { 
                        //match found

                        return true;
                    }
                }
                //match not found
                return false;

            }
        
        function getLogInCred($enteredUsername, $enteredPassword){
            //query returns password that matches the given username
            $searchUserNameQuery = "select password from log_in_cred where username = '".$enteredUsername."'";

            global $conn;
            $result = $conn->query($searchUserNameQuery);

            //checks to see if there are any rows returned 
            if ( $result-> num_rows > 0) {
                // store data of each row in variables
                while($row = $result->fetch_assoc()) {
                    //username is unique
                    $storedPassword = $row["password"];
                }

                //when both passwords match, returns True
                if (password_verify($enteredPassword, $storedPassword )){
                    //password is correct
                    return true;
                } else {
                    //password is incorrect
                    return false;
                }
            } 

        }
        
        ?>
        
        <div class="header">
            <div class="title">
                Welcome to the pet store admin site
            </div>
        </div>
        
        <div class="container">
            
            
            <div class="item" id="log_in">
                <p class="item_title">Log In</p>
                <div class="form">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<!--                        input user name-->
                        <label for="userName">User Name</label> <br>
                        <input type="text" id="userName" name="userName" value="<?php echo $userName; ?>">
                        <span><?php
                        echo "<p style='color:firebrick;'>".$userNameErr."</p>";?></span>
                        <br><br>
<!--                       input password -->
                        <label for="password">Password</label> <br>
                        <input type="password" id="password" name="password"  value="<?php echo $password; ?>">
                        <span><?php
                        echo "<p style='color:firebrick;'>".$passwordErr."</p>";?></span>
                        <br><br>
                        <span><?php
                        echo "<p style='color:firebrick;'>".$logInErr."</p>";?></span>
                        
<!--                        create new user account link-->
                        <p class="newUserLink"><a href="new_user.php">Create New Account</a></p>
<!--                       Button -->
                        <input type="submit" value="Log In">
                    </form>
                </div>
            </div>
            
        </div>
    
        
        <script src=""></script>
    </body>
</html>






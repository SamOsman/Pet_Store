<!--file created by: Salma Osman-->
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
        //open log file
        $log = fopen("log/log.txt", "a");
         
        //password will not be saved if page refreshes, username will be
        $userName = $password = $confirmPassword = $email = "";
        $userNameErr = $passwordErr = $confirmPasswordErr = $emailErr = $connErr = "";
        
        
        
        
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            
            $servername="localhost";
            $username="root";
            $password="";  
            $dbName = "pet_store_546";
            //connect to tablespace
            $conn = mysqli_connect($servername, $username, $password, $dbName);
            
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
                    //the 2 trings passed to strpos have to be identical in order for them to return true!
                    if (strpos($newUserName, $name) !== FALSE) { 
                        //match found

                        return true;
                    }
                }
                //match not found
                return false;

            }
         
            
            //user name cannot be empty or over 15 charac. long
            if (empty($_POST['username'])){
                $userNameErr = "Username is required";
            } else {
                $userName = testString($_POST['username']);
                if ( strlen( $userName ) > 15 ){
                    $userNameErr = "Username cannot contain more than 15 characters";
                } else {
                    //user name must be unique!!!!!!!!!!!!!!!
                    if (checkUserName($userName)) {
                        $userNameErr = "Username already exists";
                    } 
                }
            }
            
            //password cannot be empty or over 10 charac. long 
            if (empty($_POST['password'])){
                $passwordErr = "Password is required";
            } else {
                $password = testString($_POST['password']);
                if ( strlen( $password ) > 10 ){
                    $passwordErr = "Password cannot contain more than 10 characters";
                }
            }
            
            //password cannot be empty or differ from the above password
            if (empty($_POST['confirmPassword'])){
                $confirmPasswordErr = "Password confirmation is required";
            } else {
                $confirmPassword = testString($_POST['confirmPassword']);
                if ( $password !== $confirmPassword ){
                    $confirmPasswordErr = "Password confirmation failed";
                }
            }
            
            if (empty($_POST['email'])){
                $emailErr = "Please enter your email (we use it in case you forget your password)";
            } else {
                $email = testString( $_POST['email'] );
                //evaluates to true if email is VALID, evaluates to flase if email is IVALID
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    //for testing only!!!!!!!!!!!!!!!!!!
                    $emailErr =  "email address is considered invalid.";
                    
                }
                
            }
            
            //only when all the error messages are mepty, can  a user's info be inserted into the database
            if ( $userNameErr === "" && $passwordErr === "" && $confirmPasswordErr === "" && $emailErr === "") 
            {
                //hash password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));
                //insert sql query
                $insert = "insert into log_in_cred values ('".$userName."','".$hashedPassword."','".$email."');";
                
                //insert new account in database
               if ($conn){
                
                   if ( mysqli_query($conn,$insert)){
                       //new user succesfully inserted in the table
                       //send user to the main page
                        header("Location: Index.html");
                        exit();
                   } else {
                       //user could not be added to table, log in log file
                       fwrite($log, "Creating New User Error: could not insert new user in table");
                   }
                    
                } else {
                   
                   fwrite($log, "Creating New User Error: Could not establish connection with database Petstore");
                   
               }
                
                //username and password inserted in session variable and displayed in the log in page
            }
            
        }
        
        //cleans up data recieved from html form/database 
        function testString($data){
                $data=trim($data);
                $data=stripslashes($data);
                $data=htmlspecialchars($data);
                return $data;
            }
        
        $error = $userNameErr."\n".$passwordErr."\n".$confirmPasswordErr."\n".$emailErr."\n".$connErr;
        fwrite($log, $error);              
        fclose($log);          
        
        ?>
        
        <div class="header">
            <div class="title">
                Welcome to the pet store admin site
            </div>
        </div>
        
        <div class="container">
            
            <div class="item" id="new_user">
                <p class="item_title">New User</p>
                <div class="form">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<!--                        user name-->
                        <label for="usernme">Choose Username</label> <br>
                        <input type="text" id="username" name="username" value="<?php echo $userName;?>">
                        <span><?php
                        echo "<p style='color:firebrick;'>".$userNameErr."</p>";?></span>
                        <br>
<!--                        password-->
                        <label for="password">Choose password</label> <br>
                        <input type="password" id="password" name="password" value="<?php echo $password;?>">
                        <span><?php
                        echo "<p style='color:firebrick;'>".$passwordErr."</p>";?></span>
                        <br>
<!--                        password confirmation-->
                        <label for="confirmPassword">Confirm password</label> <br>
                        <input type="password" id="confirmPassword" name="confirmPassword">
                        <span><?php
                        echo "<p style='color:firebrick;'>".$confirmPasswordErr."</p>";?></span>
                        <br>
<!--                       email -->
                        <label for="email">Email</label> <br>
                        <input type="text" id="email" name="email" value="<?php echo $email;?>">
                        <span><?php
                        echo "<p style='color:firebrick;'>".$emailErr."</p>";?></span>
                        <br>
                        
                        <span><?php
                        echo "<p style='color:firebrick;'>".$connErr."</p>";?></span>
<!--                        button-->
                        <input type="submit" value="Create Account">
                    </form>
                </div>
            </div>
        
                        
        </div>
        
        
    
        
        <script src=""></script>
    </body>
</html>
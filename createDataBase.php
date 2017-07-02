<!--file created by: Salma Osman-->
<?php

// http://localhost:81/phpmyadmin/    <-- database stored there

$servername="localhost";
$username="root";
$password="";

///-------Variable that hold error messages---------
$connection_test =  $create_tablespace = $connect_tablespace =   $create_tables = $table_exists /*will be used in the log in page to check whether this page ran */ = "";



//set up connection object
$conn = mysqli_connect($servername, $username, $password);

//Test connection
if(!$conn){
    //kills the connection and displays to use:
    $connection_test = die("Connection failed: ".mysqli_connect_error()."<br>");
        
} else {
    $connection_test = "connection is succesful <br>";
}

//create a tablespace
$db = "create database pet_store_546";

if (mysqli_query($conn, $db)){
    $create_tablespace =  "Database had been created! <br>"; 
    $table_exists = "true";

} else {
    $create_tablespace = mysqli_error($conn)."<br>";
    $table_exists = "false";
}


//connect to tablespace
$dbName = "pet_store_546";
$conn = mysqli_connect($servername, $username, $password, $dbName);

//test if connection to the pet_store database is succesful
if(!$conn)
{
   $connect_tablespace =  die("Connection failed:  ".mysqli_connect_error()."<br>");
} else {
    
    $connect_tablespace =  "Pet Store database Connection is successful! <br>";
}


//create 'log_in_cred' table
$table_1 = "create table log_in_cred (
            username varchar(15) primary key not null,
            password varchar(64) not null,
            email  varchar(25) not null )";

if (mysqli_query($conn, $table_1)){
    $create_table_1 = "Table 'log_in_cred' has been created <br>";

} else {
    $create_table_1 = "Table 'log_in_cred' has NOT been created <br>";
}

//create 'customer' table
$table_2 = "create table customer (
            cust_id int primary key AUTO_INCREMENT,
            firstname varchar(15) not null,
            lastname varchar(15)  not null,
            phone int(10),
            email varchar(25),
            city varchar(25)
    )";

if (mysqli_query($conn, $table_2)){
    $create_table_2 = "Table 'Customer' has been created <br>";

} else {
    $create_table_2 = "Table 'Customer' has NOT been created <br>";
}

//create 'pet' table
$table_3 = "create table pet (
            pet_id int primary key AUTO_INCREMENT,
            name varchar(20) not null,
            price float(5,2)  not null,
            description varchar(50),
            breed varchar(25),
            sex varchar(25),
            color varchar(25),
            hair varchar(25),
            age varchar(25),
            available int(1) )";

if (mysqli_query($conn, $table_3)){
    $create_table_3 = "Table 'pet' has been created <br>";

} else {
    $create_table_3 = "Table 'pet' has NOT been created <br>";
}


//create 'transactions' table
$table_4 = "create table transactions (
            transaction_id int primary key AUTO_INCREMENT,
            cust_id int not null,
            pet_id int  not null,
            payment_date datetime not null,
            payment_method varchar(25) not null,
            payment_amount float(5, 2) not null,
            FOREIGN KEY (cust_id) REFERENCES customer(cust_id),
            FOREIGN KEY (pet_id) REFERENCES pet(pet_id)
            )";

if (mysqli_query($conn, $table_4)){
    $create_table_4 = "Table 'transactions' has been created <br>";

} else {
    $create_table_4 = "Table 'transactions' has NOT been created <br>";
}




//write errors to log file 

$log = fopen("log/log.txt", "w") or die("Unable to open log file!");

$error = $table_exists."\n".$connection_test."\n".$create_tablespace."\n".$connect_tablespace."\n".$create_table_1."\n".$create_table_2."\n".$create_table_3."\n".$create_table_4."\n";

fwrite($log, $error);
fclose($log);
















?>
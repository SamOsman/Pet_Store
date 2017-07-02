<!--file created by: Salma Osman-->
<!DOCTYPE html>
<?php session_start(); ?>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require 'resources/index_res.php'; ?>
    <title>Home</title>
</head>

<body>
    <div class="header">
       <?php include 'Menu/header.php'; ?>
        
    </div>
        
    <div class="container">


        <a href="new_pet.php">
            <div class="item" id="">
               <p class="item_title">New Pet</p>

            
            </div>
        </a>
        
        <a href="regNewUser.php">
            <div class="item" id="">
               <p class="item_title">New Customer</p>

            
            </div>
        </a>
        
        <a href="transactions.php">
            <div class="item" id="">
               <p class="item_title">Make A Transaction</p>

            
            </div>
        </a>

    </div>
  
    
    
    <script src=""></script>
</body>
</html>

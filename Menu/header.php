<!-- Universal header section -->

<!--this will check to see if the user session was set-->
<?php require 'Menu/checkUser.php'; ?>

<!DOCTYPE html>
<div class="title">
 
    Welcome, you are logged in as: <?php echo $userName; ?>
    
    <a href="log_in.php" >Log out</a>
</div>

<div class="menu-container">
    <ul>
        <li class="one"><a href="Index.php">Home</a></li>
        <li class="two"><a href="new_pet.php">New Pet</a></li>
        <li class="three"><a href="new_customer.php">New Customer</a></li>
        <li class="four"><a href="transactions.php">Transactions</a></li>
        <hr/>
    </ul>
</div>
        
   
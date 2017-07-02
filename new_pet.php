<!--file created by: Salma Osman-->
<!DOCTYPE html>
<?php
session_start();
$nameErr = $priceErr = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require 'DB/connect_db.php';
    
    if (empty($_POST["name"])) {
        $nameErr = "Pet Name is Required";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["price"])) {
        $priceErr = "Price is Required";
    } else {
        $price = $_POST["price"];
    }

    $desc = $_POST["desc"];
    $breed = $_POST["breed"];
    $sex = $_POST["sex"];
    $color = $_POST["color"];
    $hair = $_POST["hair"];
    $age = $_POST["age"];

    if ($nameErr == "" & $priceErr == "") {
        $query = "INSERT INTO pet (name, price, description, breed, sex, color, hair, age, available) VALUES ('$name', '$price', '$desc', '$breed', '$sex', '$color', '$hair', '$age', 1)";

        if (mysqli_query($conn, $query)) {
            //echo "Customer Registered";
            $msg = "Pet Registered";
            
        } else {
//            echo die("Customer not registered: ".mysqli_connect_error());
            $msg = "Pet not registered";
        }   
    }
}

?>

<html>
    <head>
        <title>New Pet</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php require 'resources/pet_res.php'; ?>
        
    </head>
    <body>
         <div class="header">
           <?php  require 'Menu/header.php'?>
        </div>
               
        <div class="container">
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="flex-container">
                    
                    <div class="item-title">
                        <h1>Add a new pet</h1>
                        <a href="edit_pet.php" >Remove Existing Pet</a>
                    </div>
                    <div class="item-table">
                        <table>
                            <tr>
                                <td>
                                     <label for="name">Pet name:</label><span class="error">*</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" aria-discribedby="NameHelp" id="name" name="name"/>
                                    <span class="error"><?php echo $nameErr; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                 <label for="price">Price:</label><span style="color:red">*</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control" aria-discribedby="price" id="price" name="price"/>
                                    <span class="error"><?php echo $priceErr; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="desc">Description:</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" aria-discribedby="desc" id="desc" name="desc"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                 <label for="breed">Breed:</label>
                                </td>
                                <td>
                                <input type="text" class="form-control" aria-discribedby="breed" id="breed" name="breed"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="sex">Sex:</label>
                                </td>
                                <td>
    <!--                                <input type="text" class="form-control" aria-discribedby="sex" id="sex" name="sex"/>-->
                                    <select class="dropdown" name="sex">
                                        <option class="dropdown-content">Male</option>
                                        <option class="dropdown-content">Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <label for="color">Fur color:</label>
                                </td>
                                <td>
                                <input type="text" class="form-control" aria-discribedby="color" id="color" name="color"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <label for="hair">Hair Length:</label>
                                </td>
                                <td>
    <!--                            <input type="text" class="form-control" aria-discribedby="hair" id="hair" name="hair"/>-->
                                    <select class="dropdown"name="hair">
                                        <option class="dropdown-content">Short</option>
                                        <option class="dropdown-content">Medium</option>
                                        <option class="dropdown-content">Long</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <label for="age">Age:</label>
                                </td>
                                <td>
                                <input type="number" class="ageNumber" name="age"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="item-footer">
                        <button type="submit">Submit</button>
                        <span><?php echo "<p style='color:firebrick;'>".$msg."</p>";?></span>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
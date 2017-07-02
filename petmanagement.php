<!DOCTYPE php>
<html>
<head>
<title>Pet Manager</title>
<!-- Importing bootstrap, jquery and setting the title icon -->
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">
<link rel="icon" href="addpetico.ico">
<script src="jquery-3.1.1.js"></script>

</head>
<body>

<?php

//to do

//sql query to edit an existant entry

//currently the radio buttons on the edit form
//are not automatically checked to reflect the 
//info from the search results. there is nothing
//that i can do right now to fix it.



$servername="localhost";
$username="root";
$password="";
$dbName = "pet_store_546";

//set up connection object
$conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$resultstr = "";
$showaddpetform = false;
$showsearchform = false;
$showeditform = false;
$petvarsvalid = true;
$searchresults = "";


//this is executed to ensure that the variables are initialized
//this allows the form the be created initially without the mess of php
//errors
addpetvalidation();
searchvalidation();
getanddoaction();
createpage();



function getanddoaction() {
	global $resultstr;
	global $petvarsvalid;
	global $showaddpetform;
	global $showeditform;
	//if the action var isnt set then the user has loaded
	//the page manually and this is not a result of clicking
	//on a form
	if (isset($_POST['action'])) {
		switch($_POST['action']){
			case "add":
				//attempts to add the entry to the database
				if($petvarsvalid){
					add();
				}
				else{
					$showaddpetform = true;
				}
				break;
			case "search":
				getsearchresults();
				break;
			case "edit":
				//uses the same validation as the addpetform
				if($petvarsvalid){
					edit();
				}
				else{
					$showeditform = true;
				}
				break;
			case "remove":
				remove();
				break;
			}
	}	
}

function add() {
	global $conn;
	global $resultstr;
	
	//creates a variable for available since the form only sends true of fals
	//if its yes then the if changes it to a 1 if its a no then it just stays at 0
	$available = 0;
	if($_POST['available'] == "true"){
		$available = 1;
	}
	//preparing the statement
	$stmt = $conn->prepare("INSERT INTO pet (name, price, description, breed, sex, color, hair, age, available) VALUES (:name, :price, :description, :breed, :sex, :color, :hair, :age, :available)");
	$stmt->bindParam(":name", $_POST['name']);
	$stmt->bindParam(":price", $_POST['price']);
	$stmt->bindParam(":description", $_POST['description']);
	$stmt->bindParam(":breed", $_POST['breed']);
	$stmt->bindParam(":sex", $_POST['sex']);
	$stmt->bindParam(":color", $_POST['color']);
	$stmt->bindParam(":hair", $_POST['hair']);
	$stmt->bindParam(":age", $_POST['age']);
	$stmt->bindParam(":available", $available);
	
	
	//if the sql command fails then resultstr is set to an error messages
	//if it succeeds then resultstr tells the user that it has been completed
	//and then clears the form
	if ($stmt->execute()){
		$resultstr = "Successfully added ".$_POST['name']." to the database.";
		clearform();
	}
	else{
		$resultstr = "Internal server error.";
	}
}

function edit() {
	global $conn;
	global $showaddpetform;
	global $showsearchform;
	global $resultstr;
	
	$showaddpetform = false;
	$showsearchform = true;
	
	$available = 0;
	if($_POST['available'] == "true"){
		$available = 1;
	}
	
	//prepared statement for removing the entry
	//preparing the statement
	$stmt = $conn->prepare("UPDATE pet SET name = ?, price = ?, description = ?, breed = ?, sex = ?, color = ?, hair = ?, age = ?, available = ?  where pet_id = ?");
	
	/* 
	$stmt->bindParam(":name", $_POST['name']);
	$stmt->bindParam(":price", $_POST['price']);
	$stmt->bindParam(":description", $_POST['description']);
	$stmt->bindParam(":breed", $_POST['breed']);
	$stmt->bindParam(":sex", $_POST['sex']);
	$stmt->bindParam(":color", $_POST['color']);
	$stmt->bindParam(":hair", $_POST['hair']);
	$stmt->bindParam(":age", $_POST['age']);
	$stmt->bindParam(":available", $available); */
	
	
	$temp = array($_POST['name'], $_POST['price'], $_POST['description'], $_POST['breed'], $_POST['sex'], $_POST['color'], $_POST['hair'], $_POST['age'], $available, $_POST['petid']);
	//$stmt->execute($temp));
	$stmt->execute($temp);
	clearform();
	$resultstr = "successfully changed entry.";
	
	
	getsearchresults();
	
	
}

function remove() {
	global $conn;
	global $showaddpetform;
	global $showsearchform;
	global $resultstr;
	
	$showaddpetform = false;
	$showsearchform = true;
	
	//prepared statement for removing the entry
	$stmt = $conn->prepare('DELETE FROM pet where pet_id= ?');
	
	$stmt->execute(array($_POST['petid']));
	
	getsearchresults();
	$resultstr = "successfully removed entry.";
}

//this sets a varible that represents the search results
//if the user has not searched for anything then this 
//string will be empty.
function getsearchresults(){
	global $conn;
	global $resultstr;
	global $searchresults;
	global $showaddpetform;
	global $showsearchform;
	
	$showaddpetform = false;
	$showsearchform = true;
	
	
	//$stmt = $conn->prepare("SELECT * FROM pet where ".$_POST['searchby']." like '%".$_POST['search']."%'");
	
	//for some god forsaken reason I was not able to use both args in a prepared statment. instead i have created several
	//prepared statements. the one that is chosed is dependant on what the search type is
	
	
	//if the user sends a searchvy value that isnt appropriate it will just fall back onto name
	//$stmt = $conn->prepare("SELECT * FROM pet where name like ?");
	
	switch($_POST['searchby']){
			case "name":
				$stmt = $conn->prepare("SELECT * FROM pet where name like ?");
				break;
			case "price":
				$stmt = $conn->prepare("SELECT * FROM pet where price like ?");
				break;
			case "breed":
				$stmt = $conn->prepare("SELECT * FROM pet where breed like ?");
				break;
			case "sex":
				$stmt = $conn->prepare("SELECT * FROM pet where sex like ?");
				break;
			case "color":
				$stmt = $conn->prepare("SELECT * FROM pet where color like ?");
				break;
			case "hair":
				$stmt = $conn->prepare("SELECT * FROM pet where hair like ?");
				break;
			case "age":
				$stmt = $conn->prepare("SELECT * FROM pet where age like ?");
				break;
	}
	
	
	$stmt->execute(array("%".$_POST['search']."%"));
	
	
	$stmt->execute();
	
	//$stmt->debugDumpParams();
	
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
	//get the amount of rows for use in the loop
	$rowamt = $stmt->rowCount();
	
	if ($rowamt == 0){
		$searchresults = "There are no results to display";
	}
	else{
		//sets $searchresults to be a html table using the results of the query
		$searchresults = '
		<table align="center" class="table-condensed" border="1">
			<tr>
				<th>Pet id</th>
				<th>Name</th>
				<th>Price</th>
				<th>Description</th>
				<th>Breed</th>
				<th>Sex</th>
				<th>Colour</th>
				<th>Hair</th>
				<th>Age</th>
				<th>Available?</th>
				<th>Action</th>
			</tr>
		';
		for($i = 0; $i< $rowamt; $i++){
			$available = "Yes";
			if ($results['available'] == 0){
				$available = "No";
			}
			
			$searchresults .= 
			'
			<tr>
				<form action="petmanagement.php" method="post">
				<th id="elem1" >'.$results['pet_id'].'</th>
				<th id="elem2" >'.$results['name'].'</th>
				<th id="elem3" >'.$results['price'].'</th>
				<th id="elem4" >'.$results['description'].'</th>
				<th id="elem5" >'.$results['breed'].'</th>
				<th id="elem6" >'.$results['sex'].'</th>
				<th id="elem7" >'.$results['color'].'</th>
				<th id="elem8" >'.$results['hair'].'</th>
				<th id="elem9" >'.$results['age'].'</th>
				<th id="elem10" >'.$available.'</th>
				
				
				
				<input type="hidden" name="action" value="remove" required>
				<input type="hidden" name="search" value="'.$_POST['search'].'" required>
				<input type="hidden" name="searchby" value="'.$_POST['searchby'].'" required>
				<th><input type="hidden" name="petid" value="'.$results['pet_id'].'" ><input type="submit" name="submit" value="remove" >
				</form><input type="submit" name="submit" class="edit" value="edit" >
				</th>
			</tr>
			';
			//the form includes inputs from the last search to make refreshing the results possible
			
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		$searchresults .= '</table>';
	}
}


//this validates all of the variable for the add pet form. This
//makes it possible to successfuly run a query as well as re populate
//the form is something is wrong

//this clears the form. it is used once an entry is successfully added to the database
function clearform(){
	$items = array('name', 'price', 'description', 'breed', 'sex', 'color', 'hair', 'age', 'available');
	for ($i = 0; $i < count($items); $i++){
		$_POST[$items[$i]] = "";
	}
}

function addpetvalidation(){
	global $petvarsvalid;
	global $resultstr;
	global $showaddpetform;
	
	
	
	//this is a loop that checks if all the post variables are filled. if they are not then it fills them with an empty string
	$items = array('name', 'price', 'description', 'breed', 'sex', 'color', 'hair', 'age', 'available');
	for ($i = 0; $i < count($items); $i++){
		if (!(isset($_POST[$items[$i]]))){
			$_POST[$items[$i]] = "";
			$petvarsvalid = false;
		}
	}
	
	
	if ($petvarsvalid){
		//this would all be one larger if statement but specific feedback messages are necesary
		if ($_POST['available'] == "true" || $_POST['available'] == "false"){
			
			
			//checking if the price is in the proper format
			if (!(preg_match('/^[0-9]{1,3}.[0-9]{2}$/',$_POST['price']))){
				$resultstr = "The price must be a number with 2 decimal places and can not be more then 3 digits long";
				$petvarsvalid = false;
			}
		}
		else{
			$resultstr = "changing the values on the radio buttons does not work";
			$petvarsvalid = false;
		}
	}
	
}

//ensures that the values for the search are set
//this is for buildng the page
function searchvalidation(){
	if (!(isset($_POST['search']))){
		$_POST['search'] = "";
	}
	
	//if search by isnt set, then set it to name
	if (isset($_POST['searchby'])){
		//if search by isnt a acceptable input then set it to name
		if (!( $_POST['searchby'] == "name" || $_POST['searchby'] == "price" ||$_POST['searchby'] == "breed" ||$_POST['searchby'] == "sex" ||$_POST['searchby'] == "color" ||$_POST['searchby'] == "hair" ||$_POST['searchby'] == "age")){
			$_POST['searchby'] = "name";
		}

	}
	else{
		$_POST['searchby'] = "name";
	}
	

}

function createpage() {
global $resultstr;
global $showaddpetform;
global $showsearchform;
global $searchresults;
global $showeditform;
//this is a massive echo statement that prints the page.
//it would just be plain html but php variables are required
//to appear
echo('



<style type="text/css">
html, body{
	/* setting the background and having it stretch to fit the window */
	/* background-image: url("background_test_1_by_varcolacu.jpg"); 
	background-size: 100% 100%;
    background-repeat: no-repeat; */
	
	background: -webkit-linear-gradient(left, #E0F2F1 , #4DB6AC); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(right, #E0F2F1, #4DB6AC); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(right, #E0F2F1, #4DB6AC); /* For Firefox 3.6 to 15 */
    background: linear-gradient(to right, #E0F2F1 , #4DB6AC); /* Standard syntax (must be last) */
	

	
	/* setting the bounds of the body. this allows the other elements to be sized appropriately */
	height: 100%;
	margin: 0;
	min-height: 100%;
}



.selectdiv {
	user-select: none;
	/*
	height: 100%;
	*/
	width: 48%;
	outline: solid thin;
	margin: 5px;
}
.selectdiv:hover{
	opacity: 0.5;
	outline: solid thick;
}


</style>

<div class="container text-center" style="height: 100%; width: 80%;">

<h2 id="title">Pet Manager</h2>

<!-- this is the set of divs that act as buttons that allow the user to decide whether they
want to add a new pet or search through the database -->
<div id="addpet" class="selectdiv" style="float: left;" >Add a pet</div>
<div id="search" class="selectdiv" style="float: right;" >Search database</div>
<br><br>

<!-- this is the div that display the feedback text. the feedback text is just to let the user know if the action they just
attempted has succeeded such as addding a pet. -->
<div id="feedbacktxt">
	'.$resultstr.'
</div>

<!-- this is the div that contains the form to add a new pet -->
	');
	//if the form is found to be incomplete or invalid the div will be visible
	//if it succeeded then the div will not
	if ($showaddpetform){
		echo('<div id="addform" >');
	}
	else{
		echo('<div id="addform" style="display: none;" >');
	}
	
	echo('
		<form action="petmanagement.php" method="post">
		<input type="hidden" name="action" value="add" required>
			<table align="center" class="table-condensed">
				<tr>
					<th>Name: </th>
					<th><input type="text" name="name" value="'.$_POST['name'].'" required></th>
				</tr>
				<tr>
					<th>Price: </th>
					<th><input type="text" name="price" value="'.$_POST['price'].'" required></th>
				</tr>
				<tr>
					<th>Description: </th>
					<th><input type="text" name="description" value="'.$_POST['description'].'" required></th>
				</tr>
				<tr>
					<th>Breed: </th>
					<th><input type="text" name="breed" value="'.$_POST['breed'].'" required></th>
				</tr>
				<tr>
					<th>Sex: </th>
					<th><input type="text" name="sex" value="'.$_POST['sex'].'" required></th>
				</tr>
				<tr>
					<th>Colour: </th>
					<th><input type="text" name="color" value="'.$_POST['color'].'" required></th>
				</tr>
				<tr>
					<th>Hair: </th>
					<th><input type="text" name="hair" value="'.$_POST['hair'].'" required></th>
				</tr>
				<tr>
					<th>Age: </th>
					<th><input type="text" name="age" value="'.$_POST['age'].'" required></th>
				</tr>
				<tr>
					<th>Available: </th>
					<th>
					');
					
					
					//remembers what the user checks for the radio buttons
					//if the user has just loaded the page it will be blank
					
					if($_POST['available'] == 'true'){
						echo('
						<input type="radio" name="available" value="true" checked required>Yes
						<input type="radio" name="available" value="false" required>No
						');
						
					}
					else if ($_POST['available'] == 'false'){
						echo('
						<input type="radio" name="available" value="true" required>Yes
						<input type="radio" name="available" value="false" checked required>No
						');
					}
					else{
						echo('
						<input type="radio" name="available" value="true" required>Yes
						<input type="radio" name="available" value="false" required>No
						');
					}
					
					//<input type="radio" name="available" value="true" required>Yes
					//<input type="radio" name="available" value="false" required>No
					
					
					echo('
					</th>
				</tr>
				

			</table>
		<input type="submit" name="submit" align="center" value="Continue" /><br>
		</form>
	</div>
	');
	
	if ($showsearchform){
		echo('<div id="searchform" >');
	}
	else{
		echo('<div id="searchform" style="display: none;" >');
	}
	echo('
		<form action="petmanagement.php" method="post">
			<input type="hidden" name="action" value="search" required>
			<table align="center" class="table-condensed">
				<tr>
					<th>Search for: </th>
					<th><input type="text" name="search" value="'.$_POST['search'].'" required></th>
				</tr>
				<tr>
					<th>Search by:</th>
					<th>
						<select name="searchby">
						');
						
						
						//setting the combobox
						
							/* the select box would be just these few lines but
							i made that massive switch statement to allow the user
							to use the search menu without it reseting
							<option value="name">Name</option>
							<option value="price">Price</option>
							<option value="breed">Breed</option>
							<option value="sex">Sex</option>
							<option value="color">Colour</option>
							<option value="hair">Hair</option>
							<option value="age">Age</option> 
							*/
							
							
							switch($_POST['searchby']){
								case "name":
									echo('
									<option value="name" selected>Name</option>
									<option value="price">Price</option>
									<option value="breed">Breed</option>
									<option value="sex">Sex</option>
									<option value="color">Colour</option>
									<option value="hair">Hair</option>
									<option value="age">Age</option>
									');
									break;
								case "price":
									echo('
									<option value="name">Name</option>
									<option value="price" selected>Price</option>
									<option value="breed">Breed</option>
									<option value="sex">Sex</option>
									<option value="color">Colour</option>
									<option value="hair">Hair</option>
									<option value="age">Age</option>
									');
									break;
								case "breed":
									echo('
									<option value="name">Name</option>
									<option value="price">Price</option>
									<option value="breed" selected>Breed</option>
									<option value="sex">Sex</option>
									<option value="color">Colour</option>
									<option value="hair">Hair</option>
									<option value="age">Age</option>
									');
									break;
								case "sex":
									echo('
									<option value="name">Name</option>
									<option value="price">Price</option>
									<option value="breed">Breed</option>
									<option value="sex" selected>Sex</option>
									<option value="color">Colour</option>
									<option value="hair">Hair</option>
									<option value="age">Age</option>
									');
									break;
								case "color":
									echo('
									<option value="name">Name</option>
									<option value="price">Price</option>
									<option value="breed">Breed</option>
									<option value="sex">Sex</option>
									<option value="color" selected>Colour</option>
									<option value="hair">Hair</option>
									<option value="age">Age</option>
									');
									break;
								case "hair":
									echo('
									<option value="name">Name</option>
									<option value="price">Price</option>
									<option value="breed">Breed</option>
									<option value="sex">Sex</option>
									<option value="color">Colour</option>
									<option value="hair" selected>Hair</option>
									<option value="age">Age</option>
									');
									break;
								case "age":
									echo('
									<option value="name">Name</option>
									<option value="price">Price</option>
									<option value="breed">Breed</option>
									<option value="sex">Sex</option>
									<option value="color">Colour</option>
									<option value="hair">Hair</option>
									<option value="age" selected>Age</option>
									');
									break;
								
							}
							//end of searchby select
							
							
							echo('
						</select>
						<input type="submit" name="submit" value="Continue" />
					</th>
				</tr>
			</table>
		</form>
		');
		
		echo ("$searchresults");
		
		echo('
	</div> ');
	
	if ($showeditform){
		echo('<div id="editform" >');
	}
	else{
		echo('<div id="editform" style="display: none;">');
	}
	
	//<div id="editform" style="display: none;">
	
	echo('
			<h2>Edit entry</h2>
			<form action="petmanagement.php" method="post">
				<input type="hidden" name="action" value="edit" required>
				<input type="hidden" id="editpetid" name="petid" value="" required>
				<table align="center" class="table-condensed">
					<tr>
					<th>Name: </th>
					<th><input type="text" id="editname" name="name" value="'.$_POST['name'].'" required></th>
				</tr>
				<tr>
					<th>Price: </th>
					<th><input type="text" id="editprice" name="price" value="'.$_POST['price'].'" required></th>
				</tr>
				<tr>
					<th>Description: </th>
					<th><input type="text" id="editdescription" name="description" value="'.$_POST['description'].'" required></th>
				</tr>
				<tr>
					<th>Breed: </th>
					<th><input type="text" id="editbreed" name="breed" value="'.$_POST['breed'].'" required></th>
				</tr>
				<tr>
					<th>Sex: </th>
					<th><input type="text" id="editsex" name="sex" value="'.$_POST['sex'].'" required></th>
				</tr>
				<tr>
					<th>Colour: </th>
					<th><input type="text" id="editcolour" name="color" value="'.$_POST['color'].'" required></th>
				</tr>
				<tr>
					<th>Hair: </th>
					<th><input type="text" id="edithair" name="hair" value="'.$_POST['hair'].'" required></th>
				</tr>
				<tr>
					<th>Age: </th>
					<th><input type="text" id="editage" name="age" value="'.$_POST['age'].'" required></th>
				</tr>
				<tr>
					<th>Available: </th>
					<th>
					');
					
					
					//remembers what the user checks for the radio buttons
					//if the user has just loaded the page it will be blank
					
					if($_POST['available'] == 'true'){
						echo('
						<input type="radio" id="editavailable1" name="available" value="true" checked required>Yes
						<input type="radio" id="editavailable2" name="available" value="false" required>No
						');
						
					}
					else if ($_POST['available'] == 'false'){
						echo('
						<input type="radio" id="editavailable1" name="available" value="true" required>Yes
						<input type="radio" id="editavailable2" name="available" value="false" checked required>No
						');
					}
					else{
						echo('
						<input type="radio" id="editavailable1" name="available" value="true" required>Yes
						<input type="radio" id="editavailable2" name="available" value="false" required>No
						');
					}
					
					//<input type="radio" id="editavailable1" name="available" value="true" required>Yes
					//<input type="radio" id="editavailable2" name="available" value="false" required>No
					
					
					echo('
					</th>
				</tr>
				<tr>
				<th></th>
					<th>
						<input type="submit" name="submit" align="center" value="Update" /></form>
						<input type="submit" id="cancel" align="center" value="Cancel" />
					</th>
				</tr>
			</table>
			
				
		</div>
</div>


<!-- java script(Jquery)-->
<script type="text/javascript">

  $(document).ready(function(){
    $("#addpet").click(function(){
		$("#editform").hide();
		$("#searchform").fadeOut()
        $("#addform").slideDown()
    });
    $("#search").click(function(){
		$("#editform").hide();
        $("#addform").fadeOut();
		$("#searchform").slideDown()
    });
	$(".edit").click(function(){
		$("#searchform").fadeOut();
		$("#editform").fadeIn();
		
		');
		//this gets the elements above the edit buttons then strips the values and puts them in their
		//respective values. yes i know that javascript uses var and not $ but this way its consistent
		//with the rest of the file
		echo('
		$temp = $( this ).parent().parent();
		
		$("#editpetid").val($temp.find("#elem1").html());
		$("#editname").val($temp.find("#elem2").html());
		$("#editprice").val($temp.find("#elem3").html());
		$("#editdescription").val($temp.find("#elem4").html());
		$("#editbreed").val($temp.find("#elem5").html());
		$("#editsex").val($temp.find("#elem6").html());
		$("#editcolour").val($temp.find("#elem7").html());
		$("#edithair").val($temp.find("#elem8").html());
		$("#editage").val($temp.find("#elem9").html());
		');
		
		//this is where i had code to set the radio buttons on the edit form but no matter how it was done
		//the change would cause the page to refresh. and even after using code to force the editform to fade in again
		//the cancel button would break. at the moment I can not get the radio button to work as i want them to.
		
		echo('
    });
	$("#cancel").click(function(){
        $("#editform").hide();
		$("#searchform").fadeIn();
    });
	
	$("#title").click(function(){
        $(location).attr("href","index.php");
    });
	
});
</script>

');

}
//end of printing page
?>
</body>
</html>
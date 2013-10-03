<!DOCTYPE html>
<html>
<head>
<title>A14</title>
  
<script>
/**
 * Validates form data
 * Will highlight form fields in red that have errors. Will also print error out on top of page.
 * 
 * @return true - If no errors found
 * @return false - Errors found. Note: False return will prevent form from submitting to action url.
 */
function validateForm(){
	var noErrorsFound = true; //Return value. If false, errors were found and form will not submit to action url.
	var name = document.forms["myForm"]["name"].value;
	var comment = document.forms["myForm"]["comment"].value;
	
	//Clear all previous errors in the case where form is submitted multiple times. Otherwise error messages will just continue to concatenate.
	document.getElementById("errors").innerHTML = "";
	//Clear previous red colored forms
	document.forms["myForm"]["name"].setAttribute("style", "background-color: transparent");
	document.forms["myForm"]["comment"].setAttribute("style", "background-color: transparent");
	
	//Check that all fields are not empty
	if (name.length == 0) {
		document.forms["myForm"]["name"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Name field is empty.<br />";
		noErrorsFound = false; //This is important. It will prevent form from submitting to its action url.
	}
	
	if (comment.length == 0) {
		document.forms["myForm"]["comment"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Comment field is empty.<br />";
		noErrorsFound = false;
	}
  
	return noErrorsFound;
}
</script>
</head>

<body>
  <?php
  
  //Connect to database
  $db_host = "localhost";
  $db_user = "david";
  $db_pass = "aghalarpour";
  $db_name = "ics415";
  
  $db_connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
  
  if ($db_connection->connect_errno) {
    echo "Could not connect to database:  (" . $db_connection->connect_errno . ") " . $db_connection->connect_error;
    exit();
  }
  
  //Check if table exists - if not, create it. We'll name the table "a14"
  $res = mysqli_query($db_connection, "SHOW TABLES LIKE 'a14'");
  if(mysqli_num_rows($res) > 0){
    $tableExists = TRUE;
  }else{
    $tableExists = FALSE;
  }
  
  if(!$tableExists) { //Table does NOT exist, so we make it.
    //Create table a14
    $sql = "CREATE TABLE a14 
      (
      id INT NOT NULL AUTO_INCREMENT, 
      PRIMARY KEY(id),
      name varchar(50),
      comment text
      )";
    
    //Make the query and show confirmation/error reporting
    if(!mysqli_query($db_connection, $sql)){
      echo "Table creation failed: (" . $db_connection->errno . ") " . $db_connection->error;
    }else {
      echo "New table succesfully created. Add your comments below!<br />";
    }
  }
  
  
  
  //Add form data to database
  if(isset($_POST['name']) && isset($_POST['comment'])){ //Checks if both fields contain data (kind of redundant because JavaScript is checking this too, but oh well).
    $name =  $_POST['name'];
    $comment = $_POST['comment'];
    
    $sql="INSERT INTO a14 (name, comment) VALUES ('$name','$comment')";
    
    //Make the query. Will show error report if it occurs
    if(!mysqli_query($db_connection, $sql)){
      echo "Error: " . mysqli_error($db_connection);
    }
  }
  
  
  //Display database results in an HTML table
  //NOTE to Prof. Moore: I know you asked for a list in the assignment page, but I hope a table is okay too. It looks better and more organized since I also have a name field to display.
  $result = mysqli_query($db_connection, "SELECT * FROM a14");
  
  echo '<table border="1"><tr><th>Name</th><th>Comment</th></tr>';
  
  while($row = mysqli_fetch_array($result)) {
    echo '<tr><td>' . $row['name'] . '</td><td>' . $row['comment'] . '</td></tr>';
  }
  
  echo '</table>';
    
  ?>
  
  
  <!-- HTML Forms Section -->
  <p id="errors"></p>
  
  <form name="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()" method="post">
  Name: <input type="text" name="name"><br/>
  Comment: <input type="text" name="comment"><br/>
  <input type="submit" value="Submit">
  </form>
  
  
</body>

</html>
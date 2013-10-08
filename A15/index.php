<?php
  //Set user name cookie. Must be set before any other output in script or won't work!
  if(isset($_POST['formLoginName'])){
     $formLoginName= $_POST['formLoginName'];
     setcookie("userNameCookie", $formLoginName);
     echo '<script>location.reload(true);</script>'; //Reloads the page after cookie is set. Have to do this because cookie isn't sent to the PHP cookie superglobal until page refresh
  }
?>

<!DOCTYPE html>
<html>
<head>
<title>A15</title>
  
<script>
/**
 * Validates Comment form data
 * Will highlight form fields in red that have errors. Will also print error out on top of page.
 * 
 * @return true - If no errors found
 * @return false - Errors found. Note: False return will prevent form from submitting to action url.
 */
function validateCommentForm(){
	var noErrorsFound = true; //Return value. If false, errors were found and form will not submit to action url.
	var comment = document.forms["myForm"]["comment"].value;
	
	//Clear all previous errors in the case where form is submitted multiple times. Otherwise error messages will just continue to concatenate.
	document.getElementById("errors").innerHTML = "";
	//Clear previous red colored forms
	document.forms["myForm"]["comment"].setAttribute("style", "background-color: transparent");
	
	//Check that Comment field not empty	
	if (comment.length == 0) {
		document.forms["myForm"]["comment"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Comment field is empty.<br />";
		noErrorsFound = false;
	}
  
	return noErrorsFound;
}
</script>

<script>
function validateLoginForm(){
	var noErrorsFound = true; //Return value. If false, errors were found and form will not submit to action url.
	var name = document.forms["loginForm"]["name"].value;
	
	//Clear all previous errors in the case where form is submitted multiple times. Otherwise error messages will just continue to concatenate.
	document.getElementById("errors").innerHTML = "";
	//Clear previous red colored forms
	document.forms["loginForm"]["name"].setAttribute("style", "background-color: transparent");
	
	//Check that name field not empty
	if (name.length == 0) {
		document.forms["loginForm"]["name"].setAttribute("style", "background-color: red");
		document.getElementById("errors").innerHTML += "Error: Name field is empty.<br />";
		noErrorsFound = false; //This is important. It will prevent form from submitting to its action url.
	}
  
	return noErrorsFound;
}
</script>
</head>

<body>
  
  <?php
  
  //Connect to database and create new table if doesn't exist.
  $db_host = "localhost";
  $db_user = "david";
  $db_pass = "aghalarpour";
  $db_name = "ics415";
  
  $db_connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
  
  if ($db_connection->connect_errno) {
    echo "Could not connect to database:  (" . $db_connection->connect_errno . ") " . $db_connection->connect_error;
    exit();
  }
  
  //Check if table exists - if not, create it. We'll name the table "a15"
  $res = mysqli_query($db_connection, "SHOW TABLES LIKE 'a15'");
  if(mysqli_num_rows($res) > 0){
    $tableExists = TRUE;
  }else{
    $tableExists = FALSE;
  }
  
  if(!$tableExists) { //Table does NOT exist, so we make it.
    //Create table a15
    $sql = "CREATE TABLE a15 
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
      echo "(Note: New table (a15) was succesfully created.)<br />";
    }
  }
  
  
  
  //Everything below is what handles POST data and cookies.
  
  
  if(isset($_COOKIE['userNameCookie']) && isset($_POST['comment'])) {
    echo "Logged in as: <strong>" . $_COOKIE['userNameCookie'] . "</strong><br />";
    echo "Restart your browser if you wish to change your user name. NOTE: You may have to clear your cache depending on your browser settings<br /><br />";
    
    //Insert form data to database
    $name =  mysqli_real_escape_string($db_connection, $_COOKIE['userNameCookie']);
    $comment = mysqli_real_escape_string($db_connection, $_POST['comment']);
    
    $sql="INSERT INTO a15 (name, comment) VALUES ('$name','$comment')";
    
    //Make the query. Will show error report if it occurs
    if(!mysqli_query($db_connection, $sql)){
      echo "Error: " . mysqli_error($db_connection);
    }
    
    
    //Display database results in an HTML table
    //NOTE to Prof. Moore: I know you asked for a list in the assignment page, but I hope a table is okay too. It looks better and more organized since I also have a name field to display.
    $result = mysqli_query($db_connection, "SELECT * FROM a15");
    
    $totalPosts = array(); //Will use this array to count total posts for each user
    
    echo '<table border="1"><tr><th>Name</th><th>Comment</th></tr>';
    
    while($row = mysqli_fetch_array($result)) {
      echo '<tr><td>' . $row['name'] . '</td><td>' . $row['comment'] . '</td></tr>';
      
      //Increment post count array
      if(array_key_exists($row['name'], $totalPosts)){
        $totalPosts[$row['name']]++; //Increment post count
      }
      else{
        $totalPosts[$row['name']] = 1; //Set initial
      }
    }
    
    echo '</table>';
    
    
    //Display Total Post Count table
    echo '<br /><table border="1"><tr><th>Name</th><th>Total Post Count</th></tr>';
    
    foreach ($totalPosts as $key => $value) { //Key contains name, value contains post count
      echo '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
    }
    
    echo '</table>';
    
    ?>
    
    <!-- Display Comment Form -->
    <p id="errors"></p>
    
    <form name="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateCommentForm()" method="post">
    
    Comment: <input type="text" name="comment"><br/>
    <input type="submit" value="Submit">
    </form>
    
    <?php
  }
  else if(isset($_COOKIE['userNameCookie']) && !isset($_POST['comment'])) {
    echo "Logged in as: <strong>" . $_COOKIE['userNameCookie'] . "</strong><br />";
    echo "Restart your browser if you wish to change your user name. NOTE: You may have to clear your cache depending on your browser settings<br /><br />";
    
    
    //Display database results in an HTML table
    //NOTE to Prof. Moore: I know you asked for a list in the assignment page, but I hope a table is okay too. It looks better and more organized since I also have a name field to display.
    $result = mysqli_query($db_connection, "SELECT * FROM a15");
    
    $totalPosts = array(); //Will use this array to count total posts for each user
    
    echo '<table border="1"><tr><th>Name</th><th>Comment</th></tr>';
    
    while($row = mysqli_fetch_array($result)) {
      echo '<tr><td>' . $row['name'] . '</td><td>' . $row['comment'] . '</td></tr>';
      
      //Increment post count array
      if(array_key_exists($row['name'], $totalPosts)){
        $totalPosts[$row['name']]++; //Increment post count
      }
      else{
        $totalPosts[$row['name']] = 1; //Set initial
      }
    }
    
    echo '</table>';
    
    
    //Display Total Post Count table
    echo '<br /><table border="1"><tr><th>Name</th><th>Total Post Count</th></tr>';
    
    foreach ($totalPosts as $key => $value) { //Key contains name, value contains post count
      echo '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
    }
    
    echo '</table>';
    ?>
    
    
    <!-- Display Comment Form -->
    <p id="errors"></p>
    
    <form name="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateCommentForm()" method="post">
    
    Comment: <input type="text" name="comment"><br/>
    <input type="submit" value="Submit">
    </form>
    
    <?php
  }
  else if(!isset($_COOKIE['userNameCookie'])) { //User cookie not set, so display login form.
    ?>
    
    <p>Please Log in:</p> <br />
    <form name="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateLoginForm()" method="post">
    Name: <input type="text" name="formLoginName"><br/>
    <input type="submit" value="Submit">
    
    <?php
  }
  
    
  ?>
  
  
</body>

</html>